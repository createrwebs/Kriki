import { A as w, c as S, y as k } from "../lab-keys-CG3byUca.js";
import { n as d, t as v } from "../headers-BDWougG4.js";
import { dirname as y, resolve as E } from "node:path";
import { fileURLToPath as A } from "node:url";
import { readFile as _ } from "node:fs/promises";
var T = ["../__worker__.js", "../../dist/__worker__.js"], u = null;
async function W(o) {
  const r = y(A(o));
  for (const n of T) {
    const a = E(r, n);
    try {
      return await _(a), a;
    } catch {
    }
  }
  return null;
}
async function f(o) {
  return u || (u = (async () => {
    const r = await W(o);
    return r ? _(r, "utf8") : null;
  })()), u;
}
var m = "/__worker__.js";
function j(o = {}) {
  const r = o.path ?? "/__krikkit_lab_sw__.js", n = /* @__PURE__ */ new Set([...w(), r.replace(/\?.*$/, "")]);
  return {
    name: "deepthought",
    configureServer(a) {
      a.middlewares.use(async (s, e, l) => {
        const c = s.url?.split("?")[0];
        if (c === m) {
          const t = await f(import.meta.url).catch(() => null);
          if (t === null) return l();
          e.setHeader("Content-Type", "application/javascript; charset=utf-8"), e.setHeader("Cache-Control", "no-cache"), e.statusCode = 200, e.end(t);
          return;
        }
        if (!c || !n.has(c)) return l();
        try {
          const t = await d(import.meta.url), i = v();
          for (const [p, h] of Object.entries(i)) e.setHeader(p, h);
          e.statusCode = 200, e.end(t);
        } catch (t) {
          e.statusCode = 500, e.setHeader("Content-Type", "text/plain");
          const i = t instanceof Error ? t.message : String(t);
          e.end(`[lab-runtime/vite] failed to read service worker source: ${i}`);
        }
      });
    },
    async generateBundle() {
      const a = await d(import.meta.url);
      for (const e of [S, k]) this.emitFile({
        type: "asset",
        fileName: e,
        source: a
      });
      const s = await f(import.meta.url).catch(() => null);
      s !== null && this.emitFile({
        type: "asset",
        fileName: m.replace(/^\/+/, ""),
        source: s
      });
    }
  };
}
export {
  j as deepthought,
  j as default
};

//# sourceMappingURL=vite.mjs.map