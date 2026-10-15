import { r as Y } from "./pako.esm-CwBHObRx.js";
import { C as te, D as ne, E as re, M as ue, O as oe, S as se, T as ae, in as de, j as ce, k as ve, l as be, sn as N, t as ge, u as Ie, w as Le } from "./host-CsosBQTX.js";
import { j as f } from "./lab-keys-CG3byUca.js";
import { d as Ve, m as W, p as Oe } from "./offload-CgLcUAhI.js";
import { existsSync as w, readFileSync as m } from "node:fs";
import { dirname as j, join as g } from "node:path";
import { fileURLToPath as P } from "node:url";
import { Worker as k } from "node:worker_threads";
import y from "node:http";
import { createHash as D } from "node:crypto";
import { mkdir as C, readFile as V, readdir as T, unlink as E, writeFile as R } from "node:fs/promises";
import { tmpdir as q } from "node:os";
function G() {
  return `"use strict";
const { parentPort, workerData } = require("node:worker_threads");
if (!parentPort) throw new Error("deepthought worker shim: missing parentPort");

const messageListeners = new Set();
const errorListeners = new Set();

function emitMessage(data) {
  const ev = { data };
  for (const fn of messageListeners) {
    try { fn(ev); } catch (err) { console.error(err); }
  }
  if (typeof globalThis.onmessage === "function") {
    try { globalThis.onmessage(ev); } catch (err) { console.error(err); }
  }
}

globalThis.self = globalThis;
globalThis.postMessage = function postMessage(data, transfer) {
  if (Array.isArray(transfer) && transfer.length) {
    parentPort.postMessage(data, transfer);
  } else {
    parentPort.postMessage(data);
  }
};
globalThis.addEventListener = function addEventListener(type, fn) {
  if (type === "message") messageListeners.add(fn);
  else if (type === "error") errorListeners.add(fn);
};
globalThis.removeEventListener = function removeEventListener(type, fn) {
  if (type === "message") messageListeners.delete(fn);
  else if (type === "error") errorListeners.delete(fn);
};
globalThis.onmessage = null;
globalThis.onerror = null;

parentPort.on("message", function (data) { emitMessage(data); });
parentPort.on("error", function (err) {
  const ev = { message: err && err.message ? err.message : String(err), error: err };
  for (const fn of errorListeners) {
    try { fn(ev); } catch (_) { /* ignore */ }
  }
  if (typeof globalThis.onerror === "function") {
    try { globalThis.onerror(ev); } catch (_) { /* ignore */ }
  }
});

const source = workerData && workerData.__deepthoughtSource;
if (typeof source !== "string" || !source) {
  throw new Error("deepthought worker shim: missing __deepthoughtSource");
}
(0, eval)(source);
`;
}
function H(t) {
  const o = /* @__PURE__ */ new Set(), s = /* @__PURE__ */ new Set();
  let a = null, n = null;
  return t.on("message", (e) => {
    const r = { data: e };
    for (const c of o) try {
      c(r);
    } catch {
    }
    if (a) try {
      a(r);
    } catch {
    }
  }), t.on("error", (e) => {
    const r = {
      message: e.message,
      error: e
    };
    for (const c of s) try {
      c(r);
    } catch {
    }
    if (n) try {
      n(r);
    } catch {
    }
  }), {
    postMessage(e, r) {
      r && r.length ? t.postMessage(e, r) : t.postMessage(e);
    },
    terminate() {
      t.terminate();
    },
    addEventListener(e, r) {
      e === "message" ? o.add(r) : e === "error" && s.add(r);
    },
    removeEventListener(e, r) {
      e === "message" ? o.delete(r) : e === "error" && s.delete(r);
    },
    get onmessage() {
      return a;
    },
    set onmessage(e) {
      a = e;
    },
    get onerror() {
      return n;
    },
    set onerror(e) {
      n = e;
    }
  };
}
function O(t, o) {
  return H(new k(G(), {
    eval: !0,
    workerData: { __deepthoughtSource: t },
    ...o ? { name: o } : {}
  }));
}
function M(t) {
  const o = t.host ?? "127.0.0.1", s = t.port ?? 0;
  let a = null, n = null;
  async function e(d) {
    const u = [];
    for await (const v of d) u.push(Buffer.isBuffer(v) ? v : Buffer.from(v));
    return Buffer.concat(u);
  }
  function r(d) {
    const u = {};
    for (const [v, b] of Object.entries(d))
      b != null && (u[v] = Array.isArray(b) ? b.join(", ") : b);
    return u;
  }
  async function c(d, u) {
    try {
      const v = new URL(d.url || "/", `http://${o}`), b = v.pathname.split("/").filter(Boolean);
      if (b[0] !== "__virtual__" || b.length < 3) {
        u.statusCode = 404, u.setHeader("Content-Type", "text/plain"), u.end("Not Found");
        return;
      }
      const l = b[1], X = Number(b[2]);
      if (!Number.isFinite(X)) {
        u.statusCode = 400, u.setHeader("Content-Type", "text/plain"), u.end("Invalid port");
        return;
      }
      const L = await e(d), J = L.byteLength > 0 ? L.buffer.slice(L.byteOffset, L.byteOffset + L.byteLength) : void 0, A = b.slice(3), S = (A.length ? `/${A.join("/")}` : "/") + v.search, I = await t.proxy.handleRequest(l, X, (d.method || "GET").toUpperCase(), S, r(d.headers), J);
      u.statusCode = I.statusCode;
      for (const [x, i] of Object.entries(I.headers))
        i != null && (Array.isArray(i), u.setHeader(x, i));
      const F = I.body ? Buffer.isBuffer(I.body) ? I.body : Buffer.from(I.body) : Buffer.alloc(0);
      u.end(F);
    } catch (v) {
      u.statusCode = 500, u.setHeader("Content-Type", "text/plain"), u.end(v instanceof Error ? v.message : String(v));
    }
  }
  return {
    kind: "local-http",
    get baseUrl() {
      return n;
    },
    async start() {
      if (a) return;
      a = y.createServer((u, v) => {
        c(u, v);
      }), await new Promise((u, v) => {
        a.once("error", v), a.listen(s, o, () => {
          a.off("error", v), u();
        });
      });
      const d = a.address();
      d && typeof d == "object" ? n = `http://${o}:${d.port}` : n = `http://${o}`, t.proxy.setBaseUrl(n);
    },
    async stop() {
      const d = a;
      a = null, n = null, d && await new Promise((u) => {
        d.close(() => u());
      });
    }
  };
}
var h = 2, z = 6048e5;
function B() {
  return process.env.DEEPTHOUGHT_CACHE || g(q(), "deepthought-snapshots");
}
function p(t, o) {
  const s = D("sha256").update(o).digest("hex");
  return g(t, `${s}.bin`);
}
async function Q(t = B()) {
  try {
    await C(t, { recursive: !0 });
  } catch {
    return null;
  }
  return (async () => {
    try {
      const o = await T(t), s = Date.now();
      for (const a of o)
        if (a.endsWith(".meta.json"))
          try {
            const n = await V(g(t, a), "utf8"), e = JSON.parse(n);
            if (e.schema !== h || e.createdAt != null && s - e.createdAt > z) {
              const r = a.slice(0, -10);
              await E(g(t, a)).catch(() => {
              }), await E(g(t, `${r}.bin`)).catch(() => {
              });
            }
          } catch {
          }
    } catch {
    }
  })(), {
    async get(o) {
      try {
        const s = p(t, o), a = s.replace(/\.bin$/, ".meta.json"), n = await V(a, "utf8"), e = JSON.parse(n);
        if (e.schema !== h || e.createdAt != null && Date.now() - e.createdAt > z) return null;
        const r = await V(s);
        if (r.byteLength < 4) return null;
        const c = r.readUInt32LE(0), d = r.subarray(4, 4 + c), u = r.buffer.slice(r.byteOffset + 4 + c, r.byteOffset + r.byteLength);
        return {
          manifest: JSON.parse(d.toString("utf8")),
          data: u
        };
      } catch {
        return null;
      }
    },
    async set(o, s) {
      const a = p(t, o), n = a.replace(/\.bin$/, ".meta.json"), e = Buffer.from(JSON.stringify(s.manifest), "utf8"), r = Buffer.from(s.data), c = Buffer.alloc(4);
      c.writeUInt32LE(e.byteLength, 0), await R(a, Buffer.concat([
        c,
        e,
        r
      ])), await R(n, JSON.stringify({
        schema: h,
        createdAt: Date.now()
      }));
    },
    close() {
    }
  };
}
function Z() {
  try {
    const t = j(P(import.meta.url)), o = [
      g(t, "..", "..", "__worker__.js"),
      g(t, "..", "..", "..", "dist", "__worker__.js"),
      g(process.cwd(), "dist", "__worker__.js")
    ];
    for (const s of o) if (w(s)) return s;
  } catch {
  }
  return null;
}
function U() {
  const t = Buffer.from(N, "base64");
  return Y(t, { to: "string" });
}
function K(t, o) {
  return !o && t && w(t) ? m(t, "utf8") : t && w(t) ? m(t, "utf8") : U();
}
function _(t = {}) {
  let o = t.workerPath ?? Z(), s = null;
  function a(n) {
    if (!n && s) return s;
    const e = K(o, !!n);
    return n || (s = e), e;
  }
  return {
    kind: "node",
    defaultHeadless: !0,
    canCreateWorkers() {
      return !0;
    },
    createWorker(n) {
      if (n.type === "url") {
        let c = n.url;
        if (c.startsWith("file:") && (c = P(c)), !w(c)) throw new Error(`[DeepThoughtEngine] Node host cannot load worker URL: ${n.url}`);
        const d = m(c, "utf8"), u = O(d, n.name);
        return f(u, !0), u;
      }
      if (n.type === "source") return O(n.source, n.name);
      const e = a(n.embedded), r = O(e, n.name);
      return o && !n.embedded && f(r, !0), r;
    },
    async probeProcessWorkerUrl(n) {
      if (n) {
        let r = n;
        return r.startsWith("file:") && (r = P(r)), w(r) ? (o = r, s = null, r) : null;
      }
      const e = Z();
      return e ? (o = e, s = null, e) : null;
    },
    disposeGlobalResources() {
      s = null;
    },
    async openSnapshotCache(n) {
      return n.enableSnapshotCache === !1 || n.packageStore === "memory" ? null : Q(t.cacheDir);
    },
    createHttpIngress({ proxy: n, headless: e }) {
      return e ? M({
        proxy: n,
        host: t.httpHost,
        port: t.httpPort
      }) : {
        kind: "programmatic",
        baseUrl: null
      };
    }
  };
}
W(_());
export {
  ge as DeepThoughtEngine,
  te as DeepThoughtFS,
  be as DeepThoughtFSClient,
  Ie as DeepThoughtFSClientError,
  se as DeepThoughtProcess,
  ne as DeepThoughtSWSetupError,
  oe as DependencyInstaller,
  te as LabFS,
  be as LabFSClient,
  Ie as LabFSClientError,
  se as LabProcess,
  ge as LabRuntime,
  de as MemoryVolume,
  Le as RequestProxy,
  M as createLocalHttpIngress,
  _ as createNodeHost,
  ce as discoverWorkspaces,
  ae as getProxyInstance,
  Ve as getRuntimeHost,
  ve as install,
  Q as openFsSnapshotCache,
  ue as readWorkspacePatterns,
  re as resetProxy,
  Oe as resetRuntimeHost,
  W as setRuntimeHost
};

//# sourceMappingURL=headless.mjs.map