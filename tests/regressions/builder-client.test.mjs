import test from "node:test";
import assert from "node:assert/strict";
import { readFile } from "node:fs/promises";
import {
  createBlueprint,
  validateBlueprint,
  editBlueprint,
  isUuid,
  prepareSave,
  readDraftList,
} from "../../resources/js/builder/blueprint.js";
import { builderRequest } from "../../resources/js/builder/transport.js";
import { build } from "esbuild";
import { renderToStaticMarkup } from "react-dom/server";
import { createElement } from "react";

const catalog = JSON.parse(
  await readFile(
    new URL("../../resources/builder/catalog.json", import.meta.url),
  ),
);
let counter = 0;
const id = () =>
  `00000000-0000-4000-8000-${String(++counter).padStart(12, "0")}`;
const make = () =>
  createBlueprint(
    catalog,
    {
      kind: "business",
      templateId: "business-studio",
      title: "Demo",
      summary: "",
    },
    id,
  );

test("unknown prototype-looking keys produce own enumerable errors at every schema level", () => {
  for (const key of ["constructor", "toString", "__proto__"]) {
    for (const path of [
      "",
      "shared",
      "theme",
      "pages.0",
      "pages.0.sections.0",
      "pages.0.sections.1.items.0",
    ]) {
      const blueprint = make();
      const target = path
        ? path.split(".").reduce((value, part) => value[part], blueprint)
        : blueprint;
      Object.defineProperty(target, key, {
        value: { untrusted: "text" },
        enumerable: true,
        configurable: true,
      });
      const received = JSON.parse(JSON.stringify(blueprint));
      const errors = validateBlueprint(received, catalog);
      const errorPath = path ? `${path}.${key}` : key;
      assert.ok(Object.hasOwn(errors, errorPath), errorPath);
      assert.deepEqual(Object.keys(errors), [errorPath]);
      assert.equal(errors[errorPath], "Unsupported field.");
      assert.equal(Object.getPrototypeOf(errors), Object.prototype);
      assert.equal(Object.prototype.untrusted, undefined);
    }
  }
  assert.deepEqual(validateBlueprint(make(), catalog), {});
});

test("all catalog templates create valid blueprints with globally unique IDs", () => {
  for (const template of catalog.templates)
    for (const kind of template.kinds) {
      const result = createBlueprint(
        catalog,
        { kind, templateId: template.id, title: "ตัวอย่าง", summary: "" },
        id,
      );
      assert.deepEqual(validateBlueprint(result, catalog), {});
      assert.equal(result.pages[0].route, "/");
    }
  assert.throws(() =>
    createBlueprint(
      catalog,
      { kind: "business", templateId: "missing", title: "Demo" },
      id,
    ),
  );
});
test("strict schema rejects wrong primitives, unknown fields and invalid routes without repairing", () => {
  for (const route of [
    "/Lab",
    "/lab/test",
    "/two--words",
    "/a/",
    "/a.b",
    "/a%20b",
    "//x",
    "/a?x",
    "/a#x",
    "/a\\b",
    "/a/../b",
  ]) {
    const b = make();
    b.pages[1].route = route;
    assert.ok(validateBlueprint(b, catalog)["pages.1.route"], route);
    assert.equal(b.pages[1].route, route);
  }
  const b = make();
  b.pages[1].route = "/ผลงาน";
  assert.deepEqual(validateBlueprint(b, catalog), {});
  b.pages[1].route = "/";
  b.extra = true;
  b.pages[0].navigation = 1;
  b.theme.palette = "url(evil)";
  const errors = validateBlueprint(b, catalog);
  assert.ok(
    errors.extra &&
      errors["pages.0.navigation"] &&
      errors["theme.palette"] &&
      errors["pages.1.route"],
  );
});
test("invariants cover IDs, home, navigation, sizes, counts and section variants", () => {
  const changes = [
    (b) => (b.pages[0].id = b.pages[1].id),
    (b) => (b.pages[0].route = "/home"),
    (b) => b.pages.forEach((p) => (p.navigation = false)),
    (b) => (b.pages[0].sections = []),
    (b) => (b.pages[0].sections[0].variant = "script"),
    (b) => (b.title = "x".repeat(121)),
    (b) => (b.schema_version = "1"),
    (b) =>
      (b.pages[0].sections[0].items = Array.from({ length: 7 }, () => ({
        id: id(),
        title: "x",
        body: "",
      }))),
    (b) => (b.summary = "😀".repeat(70000)),
  ];
  for (const change of changes) {
    const b = make();
    change(b);
    assert.ok(Object.keys(validateBlueprint(b, catalog)).length);
  }
});
test("operations are immutable, keep unaffected identities and duplicate nested IDs", () => {
  const b = make();
  const original = JSON.stringify(b);
  const p = b.pages[0];
  const updated = editBlueprint(b, {
    type: "update-section",
    pageId: p.id,
    sectionId: p.sections[0].id,
    patch: { heading: "Changed" },
  });
  assert.equal(JSON.stringify(b), original);
  assert.equal(updated.pages[0].sections[0].id, p.sections[0].id);
  const duplicate = editBlueprint(b, {
    type: "duplicate-section",
    pageId: p.id,
    sectionId: p.sections[0].id,
    idFactory: id,
  });
  assert.notEqual(duplicate.pages[0].sections[1].id, p.sections[0].id);
  assert.deepEqual(validateBlueprint(duplicate, catalog), {});
  assert.throws(() => editBlueprint(b, { type: "remove-page", pageId: p.id }));
  assert.throws(() =>
    editBlueprint(b, { type: "move-page", pageId: p.id, to: 1 }),
  );
  assert.throws(() => editBlueprint(b, { type: "add-page", idFactory: id }));
  const smaller = editBlueprint(b, {
    type: "remove-page",
    pageId: b.pages.at(-1).id,
  });
  const added = editBlueprint(smaller, { type: "add-page", idFactory: id });
  assert.equal(added.pages.at(-1).route, `/page-${smaller.pages.length + 1}`);
  assert.equal(b.pages[0].id, added.pages[0].id);
});
test("save snapshot freezes submitted content and retains create request identity", () => {
  const b = make();
  const pending = prepareSave(b, { uuid: null, revision: null }, null, id);
  b.title = "later edit";
  assert.equal(pending.blueprint.title, "Demo");
  assert.ok(isUuid(pending.requestId));
  const retry = prepareSave(b, { uuid: null, revision: null }, pending, id);
  assert.equal(retry.requestId, pending.requestId);
  assert.equal(retry.blueprint.title, "Demo");
});
test("transport sends credentials/CSRF and structured errors without retries", async () => {
  const previousFetch = globalThis.fetch;
  const previousDocument = globalThis.document;
  let calls = 0;
  globalThis.document = { querySelector: () => ({ content: "token" }) };
  try {
    globalThis.fetch = async (url, options) => {
      calls++;
      assert.equal(options.credentials, "same-origin");
      assert.equal(options.headers["X-CSRF-TOKEN"], "token");
      assert.equal(options.headers.Accept, "application/json");
      return { ok: true, status: 200, json: async () => ({ draft: {} }) };
    };
    await builderRequest("/lab/builder/drafts", { method: "POST", body: {} });
    for (const status of [422, 409, 419, 429, 503]) {
      globalThis.fetch = async () => {
        calls++;
        return {
          ok: false,
          status,
          json: async () => ({
            message: "No",
            errors: { title: ["Required"] },
            current_revision: 4,
          }),
        };
      };
      await assert.rejects(
        builderRequest("/lab/builder/drafts", { method: "PUT", body: {} }),
        (e) =>
          e.status === status &&
          e.errors.title[0] === "Required" &&
          e.current_revision === 4,
      );
    }
    globalThis.fetch = async () => {
      calls++;
      throw new TypeError("offline");
    };
    await assert.rejects(
      builderRequest("/lab/builder/drafts", { method: "POST", body: {} }),
      (e) => e.status === 0,
    );
    assert.equal(calls, 7);
  } finally {
    globalThis.fetch = previousFetch;
    globalThis.document = previousDocument;
  }
});

test("nested unknown fields, noncanonical paths and section limits are rejected", () => {
  for (const change of [
    (b) => (b.shared.url = "https://example.test"),
    (b) => (b.theme.css = "color:red"),
    (b) => (b.pages[0].html = "<script/>"),
    (b) => (b.pages[0].sections[0].url = "javascript:evil"),
    (b) => (b.pages[0].sections[1].items[0].extra = true),
    (b) => (b.pages[1].route = "/e\u0301"),
    (b) => (b.pages[1].route = "/ SPACE "),
    (b) =>
      (b.pages[0].sections = Array.from({ length: 13 }, () => ({
        ...b.pages[0].sections[0],
        id: id(),
      }))),
  ]) {
    const b = make();
    change(b);
    assert.ok(Object.keys(validateBlueprint(b, catalog)).length);
  }
  for (const route of ["/กาแฟ", "/work-samples/ปีนี้", "/é"]) {
    const b = make();
    b.pages[1].route = route;
    assert.deepEqual(validateBlueprint(b, catalog), {});
  }
});

test("UUIDs and routes reject trailing newlines; required text rejects Unicode blanks unchanged", () => {
  assert.equal(isUuid(id() + "\n"), false);
  assert.equal(isUuid("00000000-0000-0000-0000-000000000000"), false);
  for (const value of [
    "\u00a0",
    "\u0085",
    "\uFEFF",
    " \t\n\u0085",
    "\u0000",
    "\u0000\u0085",
  ]) {
    const b = make();
    b.title = value;
    assert.ok(validateBlueprint(b, catalog).title);
    assert.equal(b.title, value);
  }
  const b = make();
  b.title = "Studio\u00a0Garden";
  assert.deepEqual(validateBlueprint(b, catalog), {});
  b.pages[1].route = "/work\n";
  assert.ok(validateBlueprint(b, catalog)["pages.1.route"]);
});

test("transport rejects nested or invalid error message types before React receives them", async () => {
  const previousFetch = globalThis.fetch;
  try {
    for (const body of [
      {
        message: { detail: "invalid" },
        errors: { title: { detail: "invalid" } },
      },
      { message: "Invalid", errors: { title: [{ detail: "nested" }] } },
      { message: ["array"], errors: [] },
      { message: "Invalid", errors: { title: 42 } },
      { message: "Invalid", errors: null },
      {},
    ]) {
      globalThis.fetch = async () => ({
        ok: false,
        status: 422,
        json: async () => body,
      });
      await assert.rejects(builderRequest("/lab/builder/drafts"), (error) => {
        assert.equal(typeof error.message, "string");
        assert.deepEqual(error.errors, {});
        assert.match(error.message, /response.*could not be read/i);
        return true;
      });
    }
    globalThis.fetch = async () => ({
      ok: false,
      status: 422,
      json: async () => ({
        message: "Check details",
        errors: { title: "Required", summary: ["Too long"] },
      }),
    });
    await assert.rejects(
      builderRequest("/lab/builder/drafts"),
      (error) =>
        error.errors.title === "Required" &&
        error.errors.summary[0] === "Too long",
    );
  } finally {
    globalThis.fetch = previousFetch;
  }
});

test("actual text field preserves astral input and uses code-point guidance rather than native UTF-16 limits", async () => {
  const { BuilderApp } = await loadComponent("BuilderApp.jsx", true);
  const previousHooks = globalThis.__builderHooks;
  globalThis.__builderHooks = {
    state: (initial) => [
      typeof initial === "function" ? initial() : initial,
      () => {},
    ],
    ref: (initial) => ({ current: initial }),
  };
  const walk = (node) =>
    Array.isArray(node)
      ? node.flatMap(walk)
      : node && typeof node === "object"
        ? [node, ...walk(node.props?.children)]
        : [];
  try {
    const tree = BuilderApp({ bootstrap: { catalog, storage_ready: false } });
    const field = walk(tree).find((n) => n.props?.label === "Website title");
    let entered;
    const element = field.type({
      ...field.props,
      value: "😀".repeat(120),
      onChange: (value) => {
        entered = value;
      },
    });
    const input = walk(element).find((n) => n.type === "input");
    assert.equal(input.props.maxLength, undefined);
    input.props.onChange({ target: { value: "😀".repeat(121) } });
    assert.equal(Array.from(entered).length, 121);
    const html = renderToStaticMarkup(element);
    assert.match(html, /120\s*\/\s*120/);
  } finally {
    globalThis.__builderHooks = previousHooks;
  }
});

test("malformed successful list responses fail with readable guidance before rendering", () => {
  for (const result of [
    null,
    {},
    { drafts: null },
    { drafts: [{}] },
    {
      drafts: [
        {
          uuid: id(),
          title: "Demo",
          kind: "business",
          revision: 0,
          updated_at: "2026-10-16T00:00:00Z",
        },
      ],
    },
  ])
    assert.throws(() => readDraftList(result, catalog), /draft list/);
  assert.deepEqual(readDraftList({ drafts: [] }, catalog), []);
});

async function loadComponent(filename, fixtureHooks = false) {
  const result = await build({
    entryPoints: [`resources/js/builder/${filename}`],
    bundle: true,
    write: false,
    format: "esm",
    jsx: "automatic",
    plugins: fixtureHooks
      ? [
          {
            name: "fixture-hooks",
            setup(builder) {
              builder.onResolve({ filter: /^react$/ }, (args) =>
                args.importer.endsWith("BuilderApp.jsx")
                  ? { path: "fixture-hooks", namespace: "fixture" }
                  : undefined,
              );
              builder.onLoad({ filter: /.*/, namespace: "fixture" }, () => ({
                contents:
                  "export const useState = (...args) => globalThis.__builderHooks.state(...args); export const useRef = (...args) => globalThis.__builderHooks.ref(...args); export const useEffect = (...args) => globalThis.__builderHooks.effect?.(...args);",
              }));
            },
          },
        ]
      : [],
  });
  return import(
    `data:text/javascript;base64,${Buffer.from(result.outputFiles[0].text).toString("base64")}`
  );
}
test("real preview escapes model-looking text and renders every page without executable markup", async () => {
  const { BlueprintPreview } = await loadComponent("BlueprintPreview.jsx");
  const b = make();
  b.pages[0].sections[0].heading = "<img src=x onerror=alert(1)>";
  b.shared.footer = "<script>evil()</script>";
  const html = renderToStaticMarkup(
    createElement(BlueprintPreview, {
      blueprint: b,
      catalog,
      pageId: b.pages[0].id,
      onSelectPage: () => {},
      overview: true,
    }),
  );
  assert.equal((html.match(/class="bp-site"/g) || []).length, 5);
  assert.ok(html.includes("&lt;img src=x onerror=alert(1)&gt;"));
  assert.ok(
    !html.includes("<img") &&
      !html.includes("<script") &&
      !html.includes("<iframe"),
  );
  assert.ok(html.includes("--bp-accent:#24734b"));
});
test("actual editor save locks edits, preserves failure and recovers an uncertain atomic creation", async () => {
  const { BuilderApp } = await loadComponent("BuilderApp.jsx", true);
  const previousFetch = globalThis.fetch,
    previousDocument = globalThis.document,
    previousWindow = globalThis.window;
  const slots = [];
  let cursor = 0;
  let resolveFetch;
  let request;
  const hooks = {
    state(initial) {
      const at = cursor++;
      if (!(at in slots))
        slots[at] = typeof initial === "function" ? initial() : initial;
      return [
        slots[at],
        (value) => {
          slots[at] = typeof value === "function" ? value(slots[at]) : value;
        },
      ];
    },
    ref(initial) {
      const at = cursor++;
      slots[at] ??= { current: initial };
      return slots[at];
    },
  };
  globalThis.__builderHooks = hooks;
  globalThis.document = { querySelector: () => ({ content: "fixture-csrf" }) };
  globalThis.window = { history: { replaceState() {} }, confirm: () => false };
  const render = () => {
    cursor = 0;
    return BuilderApp({ bootstrap: { catalog, storage_ready: true } });
  };
  const nodes = (node, result = []) => {
    if (Array.isArray(node)) node.forEach((n) => nodes(n, result));
    else if (node && typeof node === "object") {
      result.push(node);
      nodes(node.props?.children, result);
    }
    return result;
  };
  const find = (tree, predicate) => nodes(tree).find(predicate);
  try {
    let tree = render();
    find(
      tree,
      (n) =>
        n.type === "button" && n.props.children === "Open design workspace",
    ).props.onClick();
    tree = render();
    const initial = slots[0];
    globalThis.fetch = async (url, options) => {
      request = { url, options };
      return await new Promise((resolve) => {
        resolveFetch = resolve;
      });
    };
    const save = find(
      tree,
      (n) => n.type === "button" && n.props.children === "Save draft",
    ).props.onClick();
    tree = render();
    assert.equal(find(tree, (n) => n.type === "fieldset").props.disabled, true);
    const payload = JSON.parse(request.options.body);
    assert.deepEqual(Object.keys(payload).sort(), ["blueprint", "request_id"]);
    assert.deepEqual(payload.blueprint, initial);
    resolveFetch({
      ok: false,
      status: 503,
      json: async () => ({ message: "Unavailable" }),
    });
    await save;
    tree = render();
    assert.equal(slots[0], initial);
    assert.equal(find(tree, (n) => n.type === "fieldset").props.disabled, true);
    assert.ok(slots[14]);
    const pendingIdentity = slots[14];
    for (const result of [
      {
        uuid: id(),
        revision: 1,
        blueprint: { ...payload.blueprint, title: "Different server design" },
      },
      { uuid: id(), revision: 2, blueprint: payload.blueprint },
    ]) {
      globalThis.fetch = async () => ({
        ok: true,
        status: 200,
        json: async () => ({ draft: result }),
      });
      await find(
        tree,
        (n) => n.type === "button" && n.props.children === "Retry save",
      ).props.onClick();
      tree = render();
      assert.equal(slots[0], initial);
      assert.equal(slots[2], null);
      assert.equal(slots[14], pendingIdentity);
      assert.match(slots[13].message, /could not be confirmed/);
    }
    let retryPayload;
    globalThis.fetch = async (url, options) => {
      if (options.method === "GET")
        return { ok: true, status: 200, json: async () => ({ drafts: [] }) };
      retryPayload = JSON.parse(options.body);
      return {
        ok: true,
        status: 200,
        json: async () => ({
          draft: {
            uuid: id(),
            revision: 1,
            blueprint: reorderKeys(payload.blueprint),
            updated_at: "fixture",
          },
        }),
      };
    };
    await find(
      tree,
      (n) => n.type === "button" && n.props.children === "Retry save",
    ).props.onClick();
    tree = render();
    assert.deepEqual(retryPayload, payload);
    assert.equal(
      find(tree, (n) => n.type === "fieldset").props.disabled,
      false,
    );
    assert.deepEqual(JSON.parse(slots[2]), initial);
    find(tree, (n) => n.props?.label === "Website title").props.onChange(
      "Local changed title",
    );
    tree = render();
    const edited = slots[0];
    assert.notEqual(slots[2], JSON.stringify(edited));
    globalThis.fetch = async () => ({
      ok: false,
      status: 409,
      json: async () => ({ message: "Changed elsewhere", current_revision: 2 }),
    });
    await find(
      tree,
      (n) => n.type === "button" && n.props.children === "Save draft",
    ).props.onClick();
    tree = render();
    assert.equal(slots[0], edited);
    assert.equal(slots[1].revision, 1);
    assert.equal(slots[13].current_revision, 2);
    await find(
      tree,
      (n) =>
        n.type === "button" &&
        n.props.children === "Reload saved draft (discards local edits)",
    ).props.onClick();
    assert.equal(slots[0], edited); // Canceling discard leaves the unsent edit intact.
    globalThis.window.confirm = () => true;
    globalThis.fetch = async () => ({
      ok: true,
      status: 200,
      json: async () => ({
        draft: { uuid: id(), revision: 1, blueprint: initial },
      }),
    });
    await find(
      tree,
      (n) =>
        n.type === "button" &&
        n.props.children === "Reload saved draft (discards local edits)",
    ).props.onClick();
    assert.equal(slots[0], edited);
    assert.match(slots[13].message, /could not be confirmed/);
    globalThis.window.confirm = () => false;
    find(
      tree,
      (n) => n.type === "button" && n.props.children === "New draft",
    ).props.onClick();
    assert.equal(slots[0], edited);
    for (const malformed of [
      null,
      {},
      { draft: null },
      { draft: { uuid: slots[1].uuid, revision: 0, blueprint: edited } },
    ]) {
      globalThis.fetch = async () => ({
        ok: true,
        status: 200,
        json: async () => malformed,
      });
      await find(
        tree,
        (n) => n.type === "button" && n.props.children === "Save draft",
      ).props.onClick();
      tree = render();
      assert.equal(slots[0], edited);
      assert.deepEqual(JSON.parse(slots[2]), initial);
      assert.match(slots[13].message, /unsupported format/);
    }
    for (const result of [
      { uuid: id(), revision: 2, blueprint: edited },
      { uuid: slots[1].uuid, revision: 3, blueprint: edited },
      {
        uuid: slots[1].uuid,
        revision: 2,
        blueprint: { ...edited, title: "Unsaved server content" },
      },
      {
        uuid: slots[1].uuid,
        revision: 2,
        blueprint: {
          ...edited,
          pages: [edited.pages[0], ...edited.pages.slice(1).reverse()],
        },
      },
    ]) {
      globalThis.fetch = async () => ({
        ok: true,
        status: 200,
        json: async () => ({ draft: result }),
      });
      await find(
        tree,
        (n) => n.type === "button" && n.props.children === "Save draft",
      ).props.onClick();
      tree = render();
      assert.equal(slots[0], edited);
      assert.deepEqual(JSON.parse(slots[2]), initial);
      assert.equal(slots[1].revision, 1);
      assert.match(slots[13].message, /could not be confirmed/);
    }
    globalThis.fetch = async () => ({
      ok: false,
      status: 422,
      json: async () =>
        JSON.parse(
          '{"message":"Check details","errors":{"__proto__":"Review this field","constructor":["Review the title"]}}',
        ),
    });
    await find(
      tree,
      (n) => n.type === "button" && n.props.children === "Save draft",
    ).props.onClick();
    tree = render();
    const errorHtml = renderToStaticMarkup(tree);
    assert.match(errorHtml, /Draft details.*Review this field/);
    assert.match(errorHtml, /Draft details.*Review the title/);
    globalThis.fetch = async (url, options) => ({
      ok: true,
      status: 200,
      json: async () =>
        options.method === "GET"
          ? {}
          : {
              draft: {
                uuid: slots[1].uuid,
                revision: 2,
                blueprint: edited,
                updated_at: "2026-10-16T00:00:00Z",
              },
            },
    });
    await find(
      tree,
      (n) => n.type === "button" && n.props.children === "Save draft",
    ).props.onClick();
    await new Promise(setImmediate);
    tree = render();
    assert.equal(slots[2], JSON.stringify(edited));
    assert.equal(slots[1].revision, 2);
    assert.match(slots[15], /was saved.*could not refresh/);
    assert.equal(slots[3].length, 1); // Saving does not erase in-session Undo.
  } finally {
    globalThis.fetch = previousFetch;
    globalThis.document = previousDocument;
    globalThis.window = previousWindow;
    delete globalThis.__builderHooks;
  }
});

function reorderKeys(value) {
  if (Array.isArray(value)) return value.map(reorderKeys);
  if (value && typeof value === "object")
    return Object.fromEntries(
      Object.keys(value)
        .reverse()
        .map((key) => [key, reorderKeys(value[key])]),
    );
  return value;
}

test("uppercase UUID query loads the same canonical draft identity", async () => {
  const { BuilderApp } = await loadComponent("BuilderApp.jsx", true);
  const previousHooks = globalThis.__builderHooks,
    previousWindow = globalThis.window,
    previousFetch = globalThis.fetch;
  const slots = [],
    effects = [];
  let cursor = 0;
  const canonical = "abcdef01-abcd-4000-8000-000000000001";
  const blueprint = make();
  const urls = [];
  globalThis.__builderHooks = {
    state(initial) {
      const at = cursor++;
      if (!(at in slots)) slots[at] = initial;
      return [
        slots[at],
        (value) => {
          slots[at] = typeof value === "function" ? value(slots[at]) : value;
        },
      ];
    },
    ref(initial) {
      const at = cursor++;
      slots[at] ??= { current: initial };
      return slots[at];
    },
    effect(callback) {
      effects.push(callback);
    },
  };
  globalThis.window = {
    location: { search: `?draft=${canonical.toUpperCase()}` },
    history: { replaceState() {} },
    addEventListener() {},
    removeEventListener() {},
  };
  globalThis.fetch = async (url) => {
    urls.push(url);
    return {
      ok: true,
      status: 200,
      json: async () =>
        url.endsWith("/drafts")
          ? { drafts: [] }
          : { draft: { uuid: canonical, revision: 1, blueprint } },
    };
  };
  try {
    BuilderApp({ bootstrap: { catalog, storage_ready: true } });
    effects[0]();
    await new Promise(setImmediate);
    assert.ok(urls.includes(`/lab/builder/drafts/${canonical.toUpperCase()}`));
    assert.equal(slots[1].uuid, canonical);
    assert.deepEqual(slots[0], blueprint);
    assert.equal(slots[13], null);
  } finally {
    globalThis.__builderHooks = previousHooks;
    globalThis.window = previousWindow;
    globalThis.fetch = previousFetch;
  }
});
