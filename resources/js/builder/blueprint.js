const UUID =
  /^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;
const RESERVED = new Set([
  "lab",
  "api",
  "login",
  "logout",
  "register",
  "dashboard",
  "settings",
  "projects",
  "assets",
  "build",
  "storage",
  "oauth",
]);
const SEGMENT =
  /^[\p{L}\p{N}][\p{L}\p{N}\p{M}]*(?:-[\p{L}\p{N}][\p{L}\p{N}\p{M}]*)*(?![\s\S])/u;
const BLANK = /^[\p{White_Space}\uFEFF\u0000]*$/u;
export const isUuid = (value) =>
  typeof value === "string" && value.length === 36 && UUID.test(value);
const uuid = () => crypto.randomUUID();

// Database JSON may reorder object keys. Array order, primitive values and all
// text remain exact when confirming that the response saved this snapshot.
export function sameBlueprint(left, right) {
  if (left === right) return true;
  if (Array.isArray(left) || Array.isArray(right))
    return (
      Array.isArray(left) &&
      Array.isArray(right) &&
      left.length === right.length &&
      left.every((value, index) => sameBlueprint(value, right[index]))
    );
  if (!left || !right || typeof left !== "object" || typeof right !== "object")
    return false;
  const keys = Object.keys(left);
  return (
    keys.length === Object.keys(right).length &&
    keys.every(
      (key) =>
        Object.hasOwn(right, key) && sameBlueprint(left[key], right[key]),
    )
  );
}

export function readDraftList(result, catalog) {
  const drafts = result?.drafts;
  if (!Array.isArray(drafts) || drafts.length > 20)
    throw new Error(
      "The saved draft list could not be read. Reload this page to try again.",
    );
  const ids = new Set();
  for (const draft of drafts) {
    if (
      !draft ||
      !isUuid(draft.uuid) ||
      ids.has(draft.uuid.toLowerCase()) ||
      typeof draft.title !== "string" ||
      BLANK.test(draft.title) ||
      Array.from(draft.title).length > 120 ||
      !catalog.kinds.some((k) => k.id === draft.kind) ||
      !Number.isInteger(draft.revision) ||
      draft.revision < 1 ||
      typeof draft.updated_at !== "string" ||
      !Number.isFinite(Date.parse(draft.updated_at))
    )
      throw new Error(
        "The saved draft list has an unsupported format. Reload this page to try again.",
      );
    ids.add(draft.uuid.toLowerCase());
  }
  return drafts;
}

export function createBlueprint(
  catalog,
  { kind, templateId, title, summary = "" },
  idFactory = uuid,
) {
  const template = catalog.templates.find(
    (t) => t.id === templateId && t.kinds.includes(kind),
  );
  if (
    !template ||
    !catalog.kinds.some(
      (k) => k.id === kind && k.template_ids.includes(templateId),
    )
  )
    throw new Error("Choose a compatible template.");
  return {
    schema_version: 1,
    kind,
    start_mode: "template",
    template_id: templateId,
    title,
    summary,
    shared: {
      brand: Array.from(title).slice(0, 80).join(""),
      footer: "ข้อมูลและเนื้อหาตัวอย่างสมมติ",
    },
    theme: { palette: "forest", font: "sans", radius: "soft" },
    pages: template.pages.map((page) => ({
      id: idFactory(),
      title: page.title,
      route: page.route,
      navigation: true,
      sections: page.sections.map((section) => ({
        id: idFactory(),
        type: section.type,
        variant: section.variant,
        heading: section.heading,
        body: section.body,
        items: section.items.map((item) => ({
          id: idFactory(),
          title: item.title,
          body: item.body,
        })),
      })),
    })),
  };
}

export function validateBlueprint(blueprint, catalog) {
  const errors = {};
  const ids = new Set();
  const routes = new Set();
  const fail = (path, message) => {
    if (!Object.hasOwn(errors, path))
      Object.defineProperty(errors, path, { value: message, enumerable: true });
  };
  const object = (value, keys, path) => {
    if (!value || typeof value !== "object" || Array.isArray(value)) {
      fail(path, "Must be an object.");
      return false;
    }
    for (const key of keys)
      if (!Object.hasOwn(value, key))
        fail(path ? `${path}.${key}` : key, "This field is required.");
    for (const key of Object.keys(value))
      if (!keys.includes(key))
        fail(path ? `${path}.${key}` : key, "Unsupported field.");
    return true;
  };
  const text = (value, min, max, path) => {
    if (
      typeof value !== "string" ||
      Array.from(value).length < min ||
      Array.from(value).length > max ||
      (min > 0 && BLANK.test(value))
    )
      fail(path, `Use ${min}–${max} characters.`);
  };
  const identity = (value, path) => {
    if (!isUuid(value) || ids.has(String(value).toLowerCase()))
      fail(path, "Use a unique UUID.");
    ids.add(String(value).toLowerCase());
  };
  const list = (value, min, max, path) => {
    if (!Array.isArray(value) || value.length < min || value.length > max) {
      fail(path, `Use ${min}–${max} entries.`);
      return false;
    }
    return true;
  };
  if (
    !object(
      blueprint,
      [
        "schema_version",
        "kind",
        "start_mode",
        "template_id",
        "title",
        "summary",
        "shared",
        "theme",
        "pages",
      ],
      "",
    )
  )
    return errors;
  let encoded;
  try {
    encoded = JSON.stringify(blueprint);
  } catch {
    fail("blueprint", "Cannot encode blueprint.");
    return errors;
  }
  if (new TextEncoder().encode(encoded).length > 128 * 1024)
    fail("blueprint", "The draft must be at most 128 KiB.");
  if (blueprint.schema_version !== 1)
    fail("schema_version", "Unsupported schema version.");
  if (blueprint.start_mode !== "template")
    fail("start_mode", "Only templates are available.");
  const kind = catalog.kinds.find((k) => k.id === blueprint.kind);
  if (!kind) fail("kind", "Choose a website kind.");
  if (
    !kind?.template_ids.includes(blueprint.template_id) ||
    !catalog.templates.some(
      (t) => t.id === blueprint.template_id && t.kinds.includes(blueprint.kind),
    )
  )
    fail("template_id", "Choose a compatible template.");
  text(blueprint.title, 1, 120, "title");
  text(blueprint.summary, 0, 2000, "summary");
  if (object(blueprint.shared, ["brand", "footer"], "shared")) {
    text(blueprint.shared.brand, 1, 80, "shared.brand");
    text(blueprint.shared.footer, 0, 240, "shared.footer");
  }
  if (object(blueprint.theme, ["palette", "font", "radius"], "theme"))
    for (const [key, values] of Object.entries({
      palette: catalog.palettes,
      font: catalog.fonts,
      radius: catalog.radii,
    }))
      if (
        typeof blueprint.theme[key] !== "string" ||
        !Object.hasOwn(values, blueprint.theme[key])
      )
        fail(`theme.${key}`, "Choose an available theme value.");
  if (list(blueprint.pages, 1, 5, "pages"))
    blueprint.pages.forEach((page, p) => {
      const path = `pages.${p}`;
      if (
        !object(page, ["id", "title", "route", "navigation", "sections"], path)
      )
        return;
      identity(page.id, `${path}.id`);
      text(page.title, 1, 80, `${path}.title`);
      text(page.route, 1, 160, `${path}.route`);
      const route = page.route;
      if (
        typeof route !== "string" ||
        route !== route.normalize("NFC") ||
        route !== route.toLowerCase() ||
        routes.has(route) ||
        (p === 0
          ? route !== "/"
          : route === "/" ||
            !route.startsWith("/") ||
            !route
              .slice(1)
              .split("/")
              .every((part) => SEGMENT.test(part)) ||
            RESERVED.has(route.split("/")[1]))
      )
        fail(
          `${path}.route`,
          "Use a unique lowercase path; Home must be /. Reserved and encoded paths are unavailable.",
        );
      routes.add(route);
      if (typeof page.navigation !== "boolean")
        fail(`${path}.navigation`, "Must be true or false.");
      if (list(page.sections, 1, 12, `${path}.sections`))
        page.sections.forEach((section, s) => {
          const sp = `${path}.sections.${s}`;
          if (
            !object(
              section,
              ["id", "type", "variant", "heading", "body", "items"],
              sp,
            )
          )
            return;
          identity(section.id, `${sp}.id`);
          const type = catalog.section_types.find((t) => t.id === section.type);
          if (!type) fail(`${sp}.type`, "Choose an available section.");
          if (!type?.variants.includes(section.variant))
            fail(`${sp}.variant`, "Choose an available layout.");
          text(section.heading, 1, 120, `${sp}.heading`);
          text(section.body, 0, 2000, `${sp}.body`);
          if (list(section.items, 0, 6, `${sp}.items`))
            section.items.forEach((item, i) => {
              const ip = `${sp}.items.${i}`;
              if (!object(item, ["id", "title", "body"], ip)) return;
              identity(item.id, `${ip}.id`);
              text(item.title, 1, 120, `${ip}.title`);
              text(item.body, 0, 500, `${ip}.body`);
            });
        });
    });
  if (
    Array.isArray(blueprint.pages) &&
    !blueprint.pages.some((p) => p?.navigation === true)
  )
    fail("pages", "Keep at least one page in navigation.");
  return errors;
}

export function editBlueprint(blueprint, operation) {
  const next = structuredClone(blueprint);
  const idFactory = operation.idFactory ?? uuid;
  const page = next.pages.find((p) => p.id === operation.pageId);
  const patch = (target, changes, keys) => {
    if (
      !target ||
      !changes ||
      Object.keys(changes).some((k) => !keys.includes(k))
    )
      throw new Error("Unsupported edit.");
    Object.assign(target, changes);
  };
  const move = (list, index, to, min = 0) => {
    if (index < min || !Number.isInteger(to) || to < min || to >= list.length)
      throw new Error("Cannot move this entry.");
    list.splice(to, 0, list.splice(index, 1)[0]);
  };
  switch (operation.type) {
    case "update-details":
      patch(next, operation.patch, ["title", "summary"]);
      break;
    case "update-theme":
      patch(next.theme, operation.patch, ["palette", "font", "radius"]);
      break;
    case "update-shared":
      patch(next.shared, operation.patch, ["brand", "footer"]);
      break;
    case "add-page": {
      if (next.pages.length >= 5)
        throw new Error("A draft can have up to five pages.");
      next.pages.push({
        id: idFactory(),
        title: "New page",
        route: `/page-${next.pages.length + 1}`,
        navigation: true,
        sections: [
          {
            id: idFactory(),
            type: "text",
            variant: "default",
            heading: "New section",
            body: "",
            items: [],
          },
        ],
      });
      break;
    }
    case "remove-page":
      if (!page || page === next.pages[0])
        throw new Error("Home cannot be removed.");
      next.pages = next.pages.filter((p) => p.id !== page.id);
      break;
    case "move-page":
      move(next.pages, next.pages.indexOf(page), operation.to, 1);
      break;
    case "update-page":
      if (
        page === next.pages[0] &&
        operation.patch.route !== undefined &&
        operation.patch.route !== "/"
      )
        throw new Error("Home path is /.");
      patch(page, operation.patch, ["title", "route", "navigation"]);
      break;
    case "add-section":
      if (!page || page.sections.length >= 12)
        throw new Error("A page can have up to twelve sections.");
      page.sections.push({
        id: idFactory(),
        type: operation.sectionType,
        variant: operation.sectionType === "hero" ? "split" : "default",
        heading: "New section",
        body: "",
        items: [],
      });
      break;
    case "remove-section":
      if (
        !page ||
        page.sections.length <= 1 ||
        !page.sections.some((s) => s.id === operation.sectionId)
      )
        throw new Error("Keep at least one section.");
      page.sections = page.sections.filter((s) => s.id !== operation.sectionId);
      break;
    case "move-section":
      if (!page) throw new Error("Page not found.");
      move(
        page.sections,
        page.sections.findIndex((s) => s.id === operation.sectionId),
        operation.to,
      );
      break;
    case "duplicate-section": {
      if (!page || page.sections.length >= 12)
        throw new Error("A page can have up to twelve sections.");
      const index = page.sections.findIndex(
        (s) => s.id === operation.sectionId,
      );
      if (index < 0) throw new Error("Section not found.");
      const section = structuredClone(page.sections[index]);
      section.id = idFactory();
      section.items = section.items.map((item) => ({
        ...item,
        id: idFactory(),
      }));
      page.sections.splice(index + 1, 0, section);
      break;
    }
    case "update-section":
      patch(
        page?.sections.find((s) => s.id === operation.sectionId),
        operation.patch,
        ["variant", "heading", "body", "items"],
      );
      break;
    case "add-widget": {
      if (!page || page.sections.length >= 12)
        throw new Error("A page can have up to twelve sections.");
      const rawItems = Array.isArray(operation.items) ? operation.items.slice(0, 6) : [];
      page.sections.push({
        id: idFactory(),
        type: operation.sectionType || "cards",
        variant: operation.variant || "default",
        heading: String(operation.heading || "New widget").slice(0, 120),
        body: String(operation.body || "").slice(0, 2000),
        items: rawItems.map((it) => ({
          id: idFactory(),
          title: String(it.title || "Item").slice(0, 120),
          body: String(it.body || "").slice(0, 500),
        })),
      });
      break;
    }
    default:
      throw new Error("Unsupported edit.");
  }
  return next;
}

// Retain the exact creation snapshot after an uncertain response. Editing is locked
// until the member retries or explicitly discards it, so a retry cannot duplicate it.
export function prepareSave(blueprint, draft, pending, idFactory = uuid) {
  if (!draft.uuid && pending) return pending;
  return {
    blueprint: structuredClone(blueprint),
    requestId: draft.uuid ? null : idFactory(),
    uuid: draft.uuid,
    revision: draft.revision,
  };
}
