// resources/js/lab/guest/protocol.js
var PROTOCOL = "krikkit-runtime-v1";
var UUID = /^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i;
var SESSION = /^[a-f0-9]{32}$/;
var FS_METHODS = /* @__PURE__ */ new Set(["readFile", "writeFile", "mkdir", "readdir", "exists", "stat", "rm", "unlink", "rmdir", "rename"]);
function isHandshake(event, { source, origin, project, session, type }) {
  const data = event?.data;
  return event?.source === source && event?.origin === origin && data?.protocol === PROTOCOL && data?.type === type && data?.project === project && data?.session === session && UUID.test(project) && SESSION.test(session);
}
function previewUrl(origin, instance, port) {
  if (!/^lab[a-zA-Z0-9_-]+$/.test(instance) || !Number.isInteger(port) || port < 1 || port > 65535) return null;
  return `${origin}/__virtual__/${instance}/${port}`;
}
function isPreviewUrl(value, origin, instance, port) {
  try {
    return new URL(value).href.replace(/\/$/, "") === previewUrl(origin, instance, port);
  } catch {
    return false;
  }
}

// resources/js/lab/lib/guestRuntime.js
function readContext() {
  const config = JSON.parse(document.getElementById("lab-runtime-config")?.textContent || "null");
  const project = location.pathname.match(/^\/lab\/([a-f0-9-]{36})(?:\/|$)/i)?.[1] || JSON.parse(document.getElementById("lab-bootstrap")?.textContent || document.getElementById("lab-preview-bootstrap")?.textContent || "null")?.uuid;
  if (!config || !UUID.test(project || "")) throw new Error("A dedicated cookie-free runtime origin is required. Open a saved project or configure the runtime domain.");
  return { config, project };
}
async function removePlatformRuntimeWorkers() {
  if (!navigator.serviceWorker) return;
  for (const registration of await navigator.serviceWorker.getRegistrations()) {
    const scripts = [registration.active, registration.waiting, registration.installing].filter(Boolean);
    if (scripts.some((worker) => {
      const url = new URL(worker.scriptURL);
      return url.origin === window.location.origin && ["/__krikkit_lab_sw__.js", "/__deepthought_sw__.js"].includes(url.pathname);
    })) await registration.unregister();
  }
  const controller = navigator.serviceWorker.controller;
  if (controller) {
    const url = new URL(controller.scriptURL);
    if (url.origin === location.origin && ["/__krikkit_lab_sw__.js", "/__deepthought_sw__.js"].includes(url.pathname)) {
      throw new Error("The legacy runtime worker has been removed. Reload this workspace to start the isolated runtime; save pending edits first.");
    }
  }
}
async function bootGuestRuntime(options) {
  const { config, project } = readContext();
  const session = Array.from(crypto.getRandomValues(new Uint8Array(16)), (byte) => byte.toString(16).padStart(2, "0")).join("");
  const origin = `${config.scheme}//r-${session}${config.host_suffix}${config.port ? `:${config.port}` : ""}`;
  const parsed = new URL(origin);
  if (!SESSION.test(session) || parsed.origin === location.origin || !["http:", "https:"].includes(parsed.protocol)) throw new Error("Invalid runtime origin.");
  const frame = document.createElement("iframe");
  frame.title = "Isolated Lab runtime";
  frame.hidden = true;
  frame.sandbox = "allow-scripts allow-same-origin";
  frame.allow = "cross-origin-isolated";
  frame.referrerPolicy = "no-referrer";
  const source = new URL("/__runtime-host__", origin);
  source.search = new URLSearchParams({ project, session, parent: location.origin }).toString();
  frame.src = source.href;
  const channel = new MessageChannel();
  let nextId = 0;
  let closed = false;
  let instance = null;
  const pending = /* @__PURE__ */ new Map();
  const processes = /* @__PURE__ */ new Map();
  const terminals = /* @__PURE__ */ new Map();
  const earlyProcessEvents = /* @__PURE__ */ new Map();
  const earlyTerminalEvents = /* @__PURE__ */ new Map();
  function close(error = new Error("Runtime closed.")) {
    if (closed) return;
    closed = true;
    for (const item of pending.values()) {
      clearTimeout(item.timer);
      item.reject(error);
    }
    pending.clear();
    for (const proc of processes.values()) proc.finish({ stdout: "", stderr: error.message, exitCode: 1 });
    processes.clear();
    for (const terminal of terminals.values()) terminal.disposeLocal();
    terminals.clear();
    channel.port1.close();
    frame.remove();
  }
  function call(method, args = [], onProgress = null) {
    if (closed) return Promise.reject(new Error("Runtime closed."));
    const id = ++nextId;
    return new Promise((resolve, reject) => {
      const timer = setTimeout(() => {
        pending.delete(id);
        reject(new Error(`Runtime ${method} timed out.`));
      }, 3e5);
      pending.set(id, { resolve, reject, timer, onProgress });
      try {
        channel.port1.postMessage({ type: "call", id, method, args });
      } catch (error) {
        clearTimeout(timer);
        pending.delete(id);
        reject(error);
      }
    });
  }
  function processEvent(data) {
    const proc = processes.get(data.process);
    if (!proc) {
      if (Number.isSafeInteger(data.process) && earlyProcessEvents.size < 64) {
        const events = earlyProcessEvents.get(data.process) || [];
        if (events.length < 64) events.push(data);
        earlyProcessEvents.set(data.process, events);
      }
      return;
    }
    if (data.type === "process-output" && ["output", "error"].includes(data.stream) && typeof data.text === "string") proc.emit(data.stream, data.text.slice(0, 65536));
    if (data.type === "process-exit" && Number.isInteger(data.result?.exitCode)) {
      proc.finish({ stdout: String(data.result.stdout || "").slice(-4194304), stderr: String(data.result.stderr || "").slice(-4194304), exitCode: data.result.exitCode });
      processes.delete(data.process);
    }
  }
  channel.port1.onmessage = ({ data }) => {
    if (closed || !data || typeof data !== "object") return;
    if (data.type === "result") {
      const item = pending.get(data.id);
      if (!item) return;
      pending.delete(data.id);
      clearTimeout(item.timer);
      if (typeof data.error === "string") item.reject(new Error(data.error.slice(0, 2e3)));
      else item.resolve(data.value);
    } else if (data.type === "server-ready") {
      if (instance && isPreviewUrl(data.url, origin, instance, data.port)) options.onServerReady?.(data.port, data.url);
    } else if (["process-output", "process-exit"].includes(data.type)) processEvent(data);
    else if (data.type === "package-progress" && typeof data.text === "string") pending.get(data.request)?.onProgress?.(data.text.slice(0, 4096));
    else if (data.type === "terminal-data" && typeof data.text === "string") {
      const terminal = terminals.get(data.terminal);
      if (terminal) terminal.xterm.write(data.text.slice(0, 65536));
      else if (Number.isSafeInteger(data.terminal) && earlyTerminalEvents.size < 16) {
        earlyTerminalEvents.set(data.terminal, `${earlyTerminalEvents.get(data.terminal) || ""}${data.text}`.slice(-65536));
      }
    }
  };
  channel.port1.start();
  try {
    await new Promise((resolve, reject) => {
      const timer = setTimeout(() => {
        window.removeEventListener("message", ready);
        reject(new Error("Isolated runtime host did not respond."));
      }, 3e4);
      function ready(event) {
        if (!isHandshake(event, { source: frame.contentWindow, origin, project, session, type: "ready" })) return;
        clearTimeout(timer);
        window.removeEventListener("message", ready);
        frame.contentWindow.postMessage({ protocol: PROTOCOL, type: "connect", project, session }, origin, [channel.port2]);
        resolve();
      }
      window.addEventListener("message", ready);
      document.body.appendChild(frame);
    });
    const result = await call("boot", [{ files: options.files, workdir: options.workdir, env: options.env }]);
    if (!/^lab[a-zA-Z0-9_-]+$/.test(result?.instance)) throw new Error("Invalid runtime instance.");
    instance = result.instance;
  } catch (error) {
    close(error);
    throw error;
  }
  const fs = Object.fromEntries([...FS_METHODS].map((name) => [name, (...args) => call("fs", [name, ...args])]));
  const packages = Object.fromEntries(["install", "installFromManifest"].map((name) => [name, (...args) => {
    const flags = args.pop() || {};
    const { onProgress, signal, ...serializable } = flags;
    return call("package", [name, ...args, serializable], onProgress);
  }]));
  return {
    fs,
    packages,
    instanceId: instance,
    origin,
    port: (port) => previewUrl(origin, instance, port),
    setPreviewScript: (script) => call("preview-script", [script]),
    request: (...args) => call("request", args),
    async spawn(cmd, args = [], opts = {}) {
      const { signal, ...serializable } = opts;
      const { process: id } = await call("spawn", [cmd, args, serializable]);
      if (!Number.isSafeInteger(id) || processes.has(id)) throw new Error("Invalid process response.");
      const listeners = /* @__PURE__ */ new Map();
      let resolveCompletion;
      let exited = false;
      const completion = new Promise((resolve) => {
        resolveCompletion = resolve;
      });
      const controller = new AbortController();
      const proc = {
        completion,
        signal: controller.signal,
        get exited() {
          return exited;
        },
        on(event, fn) {
          if (!listeners.has(event)) listeners.set(event, /* @__PURE__ */ new Set());
          listeners.get(event).add(fn);
          return proc;
        },
        emit(event, value) {
          for (const fn of listeners.get(event) || []) fn(value);
        },
        finish(result) {
          if (exited) return;
          exited = true;
          signal?.removeEventListener("abort", abort);
          proc.emit("exit", result.exitCode);
          resolveCompletion(result);
        },
        write: (text) => {
          void call("process", [id, "write", String(text)]).catch(() => {
          });
        },
        kill: () => {
          controller.abort();
          void call("process", [id, "kill"]).catch(() => {
          });
        }
      };
      const abort = () => proc.kill();
      processes.set(id, proc);
      const early = earlyProcessEvents.get(id) || [];
      earlyProcessEvents.delete(id);
      setTimeout(() => {
        for (const event of early) processEvent(event);
      }, 0);
      if (signal?.aborted) abort();
      else signal?.addEventListener("abort", abort, { once: true });
      return proc;
    },
    async createTerminal(opts) {
      const { terminal: id } = await call("terminal-create", [{ autoPrompt: opts.autoPrompt }]);
      if (!Number.isSafeInteger(id) || terminals.has(id)) throw new Error("Invalid terminal response.");
      const xterm = new opts.Terminal({ theme: opts.theme, cursorBlink: true, fontSize: 13, fontFamily: "Consolas, monospace" });
      const fitAddon = opts.FitAddon ? new opts.FitAddon() : null;
      if (fitAddon) xterm.loadAddon(fitAddon);
      const send = (method, ...values) => {
        void call("terminal", [id, method, ...values]).catch(() => {
        });
      };
      const input = xterm.onData((data) => send("input", data));
      const resize = xterm.onResize(({ cols, rows }) => send("resize", cols, rows));
      const terminal = {
        xterm,
        fitAddon,
        attach(host) {
          xterm.open(host);
          terminal.fit();
          xterm.write(earlyTerminalEvents.get(id) || "");
          earlyTerminalEvents.delete(id);
        },
        fit() {
          fitAddon?.fit();
          send("resize", xterm.cols, xterm.rows);
        },
        focus: () => xterm.focus(),
        write: (text) => xterm.write(String(text)),
        writeln: (text) => xterm.writeln(String(text)),
        showPrompt: () => send("showPrompt"),
        input: (text) => send("input", text),
        disposeLocal() {
          input.dispose();
          resize.dispose();
          xterm.dispose();
        },
        dispose() {
          send("dispose");
          terminal.disposeLocal();
          terminals.delete(id);
        }
      };
      terminals.set(id, terminal);
      return terminal;
    },
    async teardown() {
      if (closed) return;
      try {
        await call("teardown");
      } finally {
        close();
      }
    }
  };
}

// node_modules/fflate/esm/browser.js
var u8 = Uint8Array;
var u16 = Uint16Array;
var i32 = Int32Array;
var fleb = new u8([
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  1,
  1,
  1,
  1,
  2,
  2,
  2,
  2,
  3,
  3,
  3,
  3,
  4,
  4,
  4,
  4,
  5,
  5,
  5,
  5,
  0,
  /* unused */
  0,
  0,
  /* impossible */
  0
]);
var fdeb = new u8([
  0,
  0,
  0,
  0,
  1,
  1,
  2,
  2,
  3,
  3,
  4,
  4,
  5,
  5,
  6,
  6,
  7,
  7,
  8,
  8,
  9,
  9,
  10,
  10,
  11,
  11,
  12,
  12,
  13,
  13,
  /* unused */
  0,
  0
]);
var clim = new u8([16, 17, 18, 0, 8, 7, 9, 6, 10, 5, 11, 4, 12, 3, 13, 2, 14, 1, 15]);
var freb = function(eb, start) {
  var b = new u16(31);
  for (var i2 = 0; i2 < 31; ++i2) {
    b[i2] = start += 1 << eb[i2 - 1];
  }
  var r = new i32(b[30]);
  for (var i2 = 1; i2 < 30; ++i2) {
    for (var j = b[i2]; j < b[i2 + 1]; ++j) {
      r[j] = j - b[i2] << 5 | i2;
    }
  }
  return { b, r };
};
var _a = freb(fleb, 2);
var fl = _a.b;
var revfl = _a.r;
fl[28] = 258, revfl[258] = 28;
var _b = freb(fdeb, 0);
var fd = _b.b;
var revfd = _b.r;
var rev = new u16(32768);
for (i = 0; i < 32768; ++i) {
  x = (i & 43690) >> 1 | (i & 21845) << 1;
  x = (x & 52428) >> 2 | (x & 13107) << 2;
  x = (x & 61680) >> 4 | (x & 3855) << 4;
  rev[i] = ((x & 65280) >> 8 | (x & 255) << 8) >> 1;
}
var x;
var i;
var hMap = (function(cd, mb, r) {
  var s = cd.length;
  var i2 = 0;
  var l = new u16(mb);
  for (; i2 < s; ++i2) {
    if (cd[i2])
      ++l[cd[i2] - 1];
  }
  var le = new u16(mb);
  for (i2 = 1; i2 < mb; ++i2) {
    le[i2] = le[i2 - 1] + l[i2 - 1] << 1;
  }
  var co;
  if (r) {
    co = new u16(1 << mb);
    var rvb = 15 - mb;
    for (i2 = 0; i2 < s; ++i2) {
      if (cd[i2]) {
        var sv = i2 << 4 | cd[i2];
        var r_1 = mb - cd[i2];
        var v = le[cd[i2] - 1]++ << r_1;
        for (var m = v | (1 << r_1) - 1; v <= m; ++v) {
          co[rev[v] >> rvb] = sv;
        }
      }
    }
  } else {
    co = new u16(s);
    for (i2 = 0; i2 < s; ++i2) {
      if (cd[i2]) {
        co[i2] = rev[le[cd[i2] - 1]++] >> 15 - cd[i2];
      }
    }
  }
  return co;
});
var flt = new u8(288);
for (i = 0; i < 144; ++i)
  flt[i] = 8;
var i;
for (i = 144; i < 256; ++i)
  flt[i] = 9;
var i;
for (i = 256; i < 280; ++i)
  flt[i] = 7;
var i;
for (i = 280; i < 288; ++i)
  flt[i] = 8;
var i;
var fdt = new u8(32);
for (i = 0; i < 32; ++i)
  fdt[i] = 5;
var i;
var flm = /* @__PURE__ */ hMap(flt, 9, 0);
var fdm = /* @__PURE__ */ hMap(fdt, 5, 0);
var shft = function(p) {
  return (p + 7) / 8 | 0;
};
var slc = function(v, s, e) {
  if (s == null || s < 0)
    s = 0;
  if (e == null || e > v.length)
    e = v.length;
  return new u8(v.subarray(s, e));
};
var ec = [
  "unexpected EOF",
  "invalid block type",
  "invalid length/literal",
  "invalid distance",
  "stream finished",
  "no stream handler",
  ,
  // determined by compression function
  "no callback",
  "invalid UTF-8 data",
  "extra field too long",
  "date not in range 1980-2099",
  "filename too long",
  "stream finishing",
  "invalid zip data"
  // determined by unknown compression method
];
var err = function(ind, msg, nt) {
  var e = new Error(msg || ec[ind]);
  e.code = ind;
  if (Error.captureStackTrace)
    Error.captureStackTrace(e, err);
  if (!nt)
    throw e;
  return e;
};
var wbits = function(d, p, v) {
  v <<= p & 7;
  var o = p / 8 | 0;
  d[o] |= v;
  d[o + 1] |= v >> 8;
};
var wbits16 = function(d, p, v) {
  v <<= p & 7;
  var o = p / 8 | 0;
  d[o] |= v;
  d[o + 1] |= v >> 8;
  d[o + 2] |= v >> 16;
};
var hTree = function(d, mb) {
  var t = [];
  for (var i2 = 0; i2 < d.length; ++i2) {
    if (d[i2])
      t.push({ s: i2, f: d[i2] });
  }
  var s = t.length;
  var t2 = t.slice();
  if (!s)
    return { t: et, l: 0 };
  if (s == 1) {
    var v = new u8(t[0].s + 1);
    v[t[0].s] = 1;
    return { t: v, l: 1 };
  }
  t.sort(function(a, b) {
    return a.f - b.f;
  });
  t.push({ s: -1, f: 25001 });
  var l = t[0], r = t[1], i0 = 0, i1 = 1, i22 = 2;
  t[0] = { s: -1, f: l.f + r.f, l, r };
  while (i1 != s - 1) {
    l = t[t[i0].f < t[i22].f ? i0++ : i22++];
    r = t[i0 != i1 && t[i0].f < t[i22].f ? i0++ : i22++];
    t[i1++] = { s: -1, f: l.f + r.f, l, r };
  }
  var maxSym = t2[0].s;
  for (var i2 = 1; i2 < s; ++i2) {
    if (t2[i2].s > maxSym)
      maxSym = t2[i2].s;
  }
  var tr = new u16(maxSym + 1);
  var mbt = ln(t[i1 - 1], tr, 0);
  if (mbt > mb) {
    var i2 = 0, dt = 0;
    var lft = mbt - mb, cst = 1 << lft;
    t2.sort(function(a, b) {
      return tr[b.s] - tr[a.s] || a.f - b.f;
    });
    for (; i2 < s; ++i2) {
      var i2_1 = t2[i2].s;
      if (tr[i2_1] > mb) {
        dt += cst - (1 << mbt - tr[i2_1]);
        tr[i2_1] = mb;
      } else
        break;
    }
    dt >>= lft;
    while (dt > 0) {
      var i2_2 = t2[i2].s;
      if (tr[i2_2] < mb)
        dt -= 1 << mb - tr[i2_2]++ - 1;
      else
        ++i2;
    }
    for (; i2 >= 0 && dt; --i2) {
      var i2_3 = t2[i2].s;
      if (tr[i2_3] == mb) {
        --tr[i2_3];
        ++dt;
      }
    }
    mbt = mb;
  }
  return { t: new u8(tr), l: mbt };
};
var ln = function(n, l, d) {
  return n.s == -1 ? Math.max(ln(n.l, l, d + 1), ln(n.r, l, d + 1)) : l[n.s] = d;
};
var lc = function(c) {
  var s = c.length;
  while (s && !c[--s])
    ;
  var cl = new u16(++s);
  var cli = 0, cln = c[0], cls = 1;
  var w = function(v) {
    cl[cli++] = v;
  };
  for (var i2 = 1; i2 <= s; ++i2) {
    if (c[i2] == cln && i2 != s)
      ++cls;
    else {
      if (!cln && cls > 2) {
        for (; cls > 138; cls -= 138)
          w(32754);
        if (cls > 2) {
          w(cls > 10 ? cls - 11 << 5 | 28690 : cls - 3 << 5 | 12305);
          cls = 0;
        }
      } else if (cls > 3) {
        w(cln), --cls;
        for (; cls > 6; cls -= 6)
          w(8304);
        if (cls > 2)
          w(cls - 3 << 5 | 8208), cls = 0;
      }
      while (cls--)
        w(cln);
      cls = 1;
      cln = c[i2];
    }
  }
  return { c: cl.subarray(0, cli), n: s };
};
var clen = function(cf, cl) {
  var l = 0;
  for (var i2 = 0; i2 < cl.length; ++i2)
    l += cf[i2] * cl[i2];
  return l;
};
var wfblk = function(out, pos, dat) {
  var s = dat.length;
  var o = shft(pos + 2);
  out[o] = s & 255;
  out[o + 1] = s >> 8;
  out[o + 2] = out[o] ^ 255;
  out[o + 3] = out[o + 1] ^ 255;
  for (var i2 = 0; i2 < s; ++i2)
    out[o + i2 + 4] = dat[i2];
  return (o + 4 + s) * 8;
};
var wblk = function(dat, out, final, syms, lf, df, eb, li, bs, bl, p) {
  wbits(out, p++, final);
  ++lf[256];
  var _a2 = hTree(lf, 15), dlt = _a2.t, mlb = _a2.l;
  var _b2 = hTree(df, 15), ddt = _b2.t, mdb = _b2.l;
  var _c = lc(dlt), lclt = _c.c, nlc = _c.n;
  var _d = lc(ddt), lcdt = _d.c, ndc = _d.n;
  var lcfreq = new u16(19);
  for (var i2 = 0; i2 < lclt.length; ++i2)
    ++lcfreq[lclt[i2] & 31];
  for (var i2 = 0; i2 < lcdt.length; ++i2)
    ++lcfreq[lcdt[i2] & 31];
  var _e = hTree(lcfreq, 7), lct = _e.t, mlcb = _e.l;
  var nlcc = 19;
  for (; nlcc > 4 && !lct[clim[nlcc - 1]]; --nlcc)
    ;
  var flen = bl + 5 << 3;
  var ftlen = clen(lf, flt) + clen(df, fdt) + eb;
  var dtlen = clen(lf, dlt) + clen(df, ddt) + eb + 14 + 3 * nlcc + clen(lcfreq, lct) + 2 * lcfreq[16] + 3 * lcfreq[17] + 7 * lcfreq[18];
  if (bs >= 0 && flen <= ftlen && flen <= dtlen)
    return wfblk(out, p, dat.subarray(bs, bs + bl));
  var lm, ll, dm, dl;
  wbits(out, p, 1 + (dtlen < ftlen)), p += 2;
  if (dtlen < ftlen) {
    lm = hMap(dlt, mlb, 0), ll = dlt, dm = hMap(ddt, mdb, 0), dl = ddt;
    var llm = hMap(lct, mlcb, 0);
    wbits(out, p, nlc - 257);
    wbits(out, p + 5, ndc - 1);
    wbits(out, p + 10, nlcc - 4);
    p += 14;
    for (var i2 = 0; i2 < nlcc; ++i2)
      wbits(out, p + 3 * i2, lct[clim[i2]]);
    p += 3 * nlcc;
    var lcts = [lclt, lcdt];
    for (var it = 0; it < 2; ++it) {
      var clct = lcts[it];
      for (var i2 = 0; i2 < clct.length; ++i2) {
        var len = clct[i2] & 31;
        wbits(out, p, llm[len]), p += lct[len];
        if (len > 15)
          wbits(out, p, clct[i2] >> 5 & 127), p += clct[i2] >> 12;
      }
    }
  } else {
    lm = flm, ll = flt, dm = fdm, dl = fdt;
  }
  for (var i2 = 0; i2 < li; ++i2) {
    var sym = syms[i2];
    if (sym > 255) {
      var len = sym >> 18 & 31;
      wbits16(out, p, lm[len + 257]), p += ll[len + 257];
      if (len > 7)
        wbits(out, p, sym >> 23 & 31), p += fleb[len];
      var dst = sym & 31;
      wbits16(out, p, dm[dst]), p += dl[dst];
      if (dst > 3)
        wbits16(out, p, sym >> 5 & 8191), p += fdeb[dst];
    } else {
      wbits16(out, p, lm[sym]), p += ll[sym];
    }
  }
  wbits16(out, p, lm[256]);
  return p + ll[256];
};
var deo = /* @__PURE__ */ new i32([65540, 131080, 131088, 131104, 262176, 1048704, 1048832, 2114560, 2117632]);
var et = /* @__PURE__ */ new u8(0);
var dflt = function(dat, lvl, plvl, pre, post, st) {
  var s = st.z || dat.length;
  var o = new u8(pre + s + 5 * (1 + Math.ceil(s / 7e3)) + post);
  var w = o.subarray(pre, o.length - post);
  var lst = st.l;
  var pos = (st.r || 0) & 7;
  if (lvl) {
    if (pos)
      w[0] = st.r >> 3;
    var opt = deo[lvl - 1];
    var n = opt >> 13, c = opt & 8191;
    var msk_1 = (1 << plvl) - 1;
    var prev = st.p || new u16(32768), head = st.h || new u16(msk_1 + 1);
    var bs1_1 = Math.ceil(plvl / 3), bs2_1 = 2 * bs1_1;
    var hsh = function(i3) {
      return (dat[i3] ^ dat[i3 + 1] << bs1_1 ^ dat[i3 + 2] << bs2_1) & msk_1;
    };
    var syms = new i32(25e3);
    var lf = new u16(288), df = new u16(32);
    var lc_1 = 0, eb = 0, i2 = st.i || 0, li = 0, wi = st.w || 0, bs = 0;
    for (; i2 + 2 < s; ++i2) {
      var hv = hsh(i2);
      var imod = i2 & 32767, pimod = head[hv];
      prev[imod] = pimod;
      head[hv] = imod;
      if (wi <= i2) {
        var rem = s - i2;
        if ((lc_1 > 7e3 || li > 24576) && (rem > 423 || !lst)) {
          pos = wblk(dat, w, 0, syms, lf, df, eb, li, bs, i2 - bs, pos);
          li = lc_1 = eb = 0, bs = i2;
          for (var j = 0; j < 286; ++j)
            lf[j] = 0;
          for (var j = 0; j < 30; ++j)
            df[j] = 0;
        }
        var l = 2, d = 0, ch_1 = c, dif = imod - pimod & 32767;
        if (rem > 2 && hv == hsh(i2 - dif)) {
          var maxn = Math.min(n, rem) - 1;
          var maxd = Math.min(32767, i2);
          var ml = Math.min(258, rem);
          while (dif <= maxd && --ch_1 && imod != pimod) {
            if (dat[i2 + l] == dat[i2 + l - dif]) {
              var nl = 0;
              for (; nl < ml && dat[i2 + nl] == dat[i2 + nl - dif]; ++nl)
                ;
              if (nl > l) {
                l = nl, d = dif;
                if (nl > maxn)
                  break;
                var mmd = Math.min(dif, nl - 2);
                var md = 0;
                for (var j = 0; j < mmd; ++j) {
                  var ti = i2 - dif + j & 32767;
                  var pti = prev[ti];
                  var cd = ti - pti & 32767;
                  if (cd > md)
                    md = cd, pimod = ti;
                }
              }
            }
            imod = pimod, pimod = prev[imod];
            dif += imod - pimod & 32767;
          }
        }
        if (d) {
          syms[li++] = 268435456 | revfl[l] << 18 | revfd[d];
          var lin = revfl[l] & 31, din = revfd[d] & 31;
          eb += fleb[lin] + fdeb[din];
          ++lf[257 + lin];
          ++df[din];
          wi = i2 + l;
          ++lc_1;
        } else {
          syms[li++] = dat[i2];
          ++lf[dat[i2]];
        }
      }
    }
    for (i2 = Math.max(i2, wi); i2 < s; ++i2) {
      syms[li++] = dat[i2];
      ++lf[dat[i2]];
    }
    pos = wblk(dat, w, lst, syms, lf, df, eb, li, bs, i2 - bs, pos);
    if (!lst) {
      st.r = pos & 7 | w[pos / 8 | 0] << 3;
      pos -= 7;
      st.h = head, st.p = prev, st.i = i2, st.w = wi;
    }
  } else {
    for (var i2 = st.w || 0; i2 < s + lst; i2 += 65535) {
      var e = i2 + 65535;
      if (e >= s) {
        w[pos / 8 | 0] = lst;
        e = s;
      }
      pos = wfblk(w, pos + 1, dat.subarray(i2, e));
    }
    st.i = s;
  }
  return slc(o, 0, pre + shft(pos) + post);
};
var crct = /* @__PURE__ */ (function() {
  var t = new Int32Array(256);
  for (var i2 = 0; i2 < 256; ++i2) {
    var c = i2, k = 9;
    while (--k)
      c = (c & 1 && -306674912) ^ c >>> 1;
    t[i2] = c;
  }
  return t;
})();
var crc = function() {
  var c = -1;
  return {
    p: function(d) {
      var cr = c;
      for (var i2 = 0; i2 < d.length; ++i2)
        cr = crct[cr & 255 ^ d[i2]] ^ cr >>> 8;
      c = cr;
    },
    d: function() {
      return ~c;
    }
  };
};
var dopt = function(dat, opt, pre, post, st) {
  if (!st) {
    st = { l: 1 };
    if (opt.dictionary) {
      var dict = opt.dictionary.subarray(-32768);
      var newDat = new u8(dict.length + dat.length);
      newDat.set(dict);
      newDat.set(dat, dict.length);
      dat = newDat;
      st.w = dict.length;
    }
  }
  return dflt(dat, opt.level == null ? 6 : opt.level, opt.mem == null ? st.l ? Math.ceil(Math.max(8, Math.min(13, Math.log(dat.length))) * 1.5) : 20 : 12 + opt.mem, pre, post, st);
};
var mrg = function(a, b) {
  var o = {};
  for (var k in a)
    o[k] = a[k];
  for (var k in b)
    o[k] = b[k];
  return o;
};
var wbytes = function(d, b, v) {
  for (; v; ++b)
    d[b] = v, v >>>= 8;
};
function deflateSync(data, opts) {
  return dopt(data, opts || {}, 0, 0);
}
var fltn = function(d, p, t, o) {
  for (var k in d) {
    var val = d[k], n = p + k, op = o;
    if (Array.isArray(val))
      op = mrg(o, val[1]), val = val[0];
    if (ArrayBuffer.isView(val))
      t[n] = [val, op];
    else {
      t[n += "/"] = [new u8(0), op];
      fltn(val, n, t, o);
    }
  }
};
var te = typeof TextEncoder != "undefined" && /* @__PURE__ */ new TextEncoder();
var td = typeof TextDecoder != "undefined" && /* @__PURE__ */ new TextDecoder();
var tds = 0;
try {
  td.decode(et, { stream: true });
  tds = 1;
} catch (e) {
}
function strToU8(str, latin1) {
  if (latin1) {
    var ar_1 = new u8(str.length);
    for (var i2 = 0; i2 < str.length; ++i2)
      ar_1[i2] = str.charCodeAt(i2);
    return ar_1;
  }
  if (te)
    return te.encode(str);
  var l = str.length;
  var ar = new u8(str.length + (str.length >> 1));
  var ai = 0;
  var w = function(v) {
    ar[ai++] = v;
  };
  for (var i2 = 0; i2 < l; ++i2) {
    if (ai + 5 > ar.length) {
      var n = new u8(ai + 8 + (l - i2 << 1));
      n.set(ar);
      ar = n;
    }
    var c = str.charCodeAt(i2);
    if (c < 128 || latin1)
      w(c);
    else if (c < 2048)
      w(192 | c >> 6), w(128 | c & 63);
    else if (c > 55295 && c < 57344)
      c = 65536 + (c & 1023 << 10) | str.charCodeAt(++i2) & 1023, w(240 | c >> 18), w(128 | c >> 12 & 63), w(128 | c >> 6 & 63), w(128 | c & 63);
    else
      w(224 | c >> 12), w(128 | c >> 6 & 63), w(128 | c & 63);
  }
  return slc(ar, 0, ai);
}
var exfl = function(ex) {
  var le = 0;
  if (ex) {
    for (var k in ex) {
      var l = ex[k].length;
      if (l > 65535)
        err(9);
      le += l + 4;
    }
  }
  return le;
};
var wzh = function(d, b, f, fn, u, c, ce, co) {
  var fl2 = fn.length, ex = f.extra, col = co && co.length;
  var exl = exfl(ex);
  wbytes(d, b, ce != null ? 33639248 : 67324752), b += 4;
  if (ce != null)
    d[b++] = 20, d[b++] = f.os;
  d[b] = 20, b += 2;
  d[b++] = f.flag << 1 | (c < 0 && 8), d[b++] = u && 8;
  d[b++] = f.compression & 255, d[b++] = f.compression >> 8;
  var dt = new Date(f.mtime == null ? Date.now() : f.mtime), y = dt.getFullYear() - 1980;
  if (y < 0 || y > 119)
    err(10);
  wbytes(d, b, y << 25 | dt.getMonth() + 1 << 21 | dt.getDate() << 16 | dt.getHours() << 11 | dt.getMinutes() << 5 | dt.getSeconds() >> 1), b += 4;
  if (c != -1) {
    wbytes(d, b, f.crc);
    wbytes(d, b + 4, c < 0 ? -c - 2 : c);
    wbytes(d, b + 8, f.size);
  }
  wbytes(d, b + 12, fl2);
  wbytes(d, b + 14, exl), b += 16;
  if (ce != null) {
    wbytes(d, b, col);
    wbytes(d, b + 6, f.attrs);
    wbytes(d, b + 10, ce), b += 14;
  }
  d.set(fn, b);
  b += fl2;
  if (exl) {
    for (var k in ex) {
      var exf = ex[k], l = exf.length;
      wbytes(d, b, +k);
      wbytes(d, b + 2, l);
      d.set(exf, b + 4), b += 4 + l;
    }
  }
  if (col)
    d.set(co, b), b += col;
  return b;
};
var wzf = function(o, b, c, d, e) {
  wbytes(o, b, 101010256);
  wbytes(o, b + 8, c);
  wbytes(o, b + 10, c);
  wbytes(o, b + 12, d);
  wbytes(o, b + 16, e);
};
function zipSync(data, opts) {
  if (!opts)
    opts = {};
  var r = {};
  var files = [];
  fltn(data, "", r, opts);
  var o = 0;
  var tot = 0;
  for (var fn in r) {
    var _a2 = r[fn], file = _a2[0], p = _a2[1];
    var compression = p.level == 0 ? 0 : 8;
    var f = strToU8(fn), s = f.length;
    var com = p.comment, m = com && strToU8(com), ms = m && m.length;
    var exl = exfl(p.extra);
    if (s > 65535)
      err(11);
    var d = compression ? deflateSync(file, p) : file, l = d.length;
    var c = crc();
    c.p(file);
    files.push(mrg(p, {
      size: file.length,
      crc: c.d(),
      c: d,
      f,
      m,
      u: s != fn.length || m && com.length != ms,
      o,
      compression
    }));
    o += 30 + s + exl + l;
    tot += 76 + 2 * (s + exl) + (ms || 0) + l;
  }
  var out = new u8(tot + 22), oe = o, cdl = tot - o;
  for (var i2 = 0; i2 < files.length; ++i2) {
    var f = files[i2];
    wzh(out, f.o, f, f.f, f.u, f.c.length);
    var badd = 30 + f.f.length + exfl(f.extra);
    out.set(f.c, f.o + badd);
    wzh(out, o, f, f.f, f.u, f.c.length, f.o, f.m), o += 16 + badd + (f.m ? f.m.length : 0);
  }
  wzf(out, o, files.length, cdl, oe);
  return out;
}

// resources/js/lab/lib/reactPin.js
var PINNED_REACT_VERSION = "19.1.1";
function stripDepVersion(version) {
  return String(version ?? "").replace(/^[\^~>=<\s]+/, "").trim();
}
function resolvePinnedReactPair(dependencies = {}) {
  const rawReact = dependencies.react;
  const rawDom = dependencies["react-dom"];
  const react = rawReact && rawReact !== "latest" ? stripDepVersion(rawReact) : null;
  const reactDom = rawDom && rawDom !== "latest" ? stripDepVersion(rawDom) : null;
  if (react && reactDom && react === reactDom) {
    return { react, reactDom };
  }
  if (react && !reactDom) {
    return { react, reactDom: react };
  }
  if (reactDom && !react) {
    return { react: reactDom, reactDom };
  }
  return {
    react: PINNED_REACT_VERSION,
    reactDom: PINNED_REACT_VERSION
  };
}

// resources/js/lab/lib/toolchainPins.js
var TOOLCHAIN_DEV_DEPENDENCIES = {
  "@tailwindcss/vite": "^4.0.0",
  "@vitejs/plugin-react": "^5.0.0",
  tailwindcss: "^4.0.0",
  vite: "^7.0.0"
};
var RUNTIME_DEPENDENCY_PINS = {
  "class-variance-authority": "^0.7.1",
  clsx: "^2.1.1",
  "lucide-react": "^0.511.0",
  "react-router-dom": "^7.6.0",
  "tailwind-merge": "^3.3.0"
};
var TOOLCHAIN_SCRIPTS = {
  dev: "vite",
  build: "vite build",
  preview: "vite preview"
};
var CONFLICTING_TOOLCHAIN_PACKAGES = [
  "@vitejs/plugin-react-oxc",
  "@vitejs/plugin-react-swc",
  "autoprefixer",
  "esbuild",
  "postcss",
  "rolldown",
  "rolldown-vite"
];
var PLACEHOLDER_SCOPES = /* @__PURE__ */ new Set([
  "@acme",
  "@example",
  "@examples",
  "@foo",
  "@bar",
  "@baz",
  "@placeholder",
  "@demo",
  "@sample",
  "@company",
  "@org",
  "@your-org",
  "@yourorg",
  "@your-company",
  "@scope",
  "@my-org",
  "@myorg",
  "@username"
]);
var PLACEHOLDER_UNSCOPED = /* @__PURE__ */ new Set([
  "acme",
  "acme-client",
  "example-api",
  "your-package",
  "placeholder-pkg"
]);
function isUninstallableLabPackage(name, extra = []) {
  const key = String(name || "").trim();
  if (!key) return true;
  if (key.startsWith(".") || key.startsWith("/") || /^(https?:|data:|blob:)/i.test(key)) {
    return false;
  }
  for (const row of extra || []) {
    if (String(row || "").trim() === key) return true;
  }
  const lower = key.toLowerCase();
  const scope = lower.startsWith("@") ? lower.split("/")[0] : "";
  const pkg = scope ? lower.slice(scope.length + 1).split("/")[0] : lower.split("/")[0];
  if (scope && PLACEHOLDER_SCOPES.has(scope)) return true;
  if (PLACEHOLDER_UNSCOPED.has(pkg)) return true;
  if (/(^|[-_/])acme([-_/]|$)/.test(lower)) return true;
  return false;
}
function stripUninstallableDeps(bag = {}) {
  const next = { ...bag };
  for (const name of Object.keys(next)) {
    if (isUninstallableLabPackage(name)) delete next[name];
  }
  return next;
}
function normalizeLabPackageJson(raw) {
  let pkg;
  try {
    pkg = JSON.parse(String(raw ?? ""));
  } catch {
    return { body: String(raw ?? ""), changed: false };
  }
  if (!pkg || typeof pkg !== "object" || Array.isArray(pkg)) {
    return { body: String(raw ?? ""), changed: false };
  }
  const before = JSON.stringify(pkg);
  pkg.private = true;
  pkg.type = "module";
  pkg.scripts = { ...pkg.scripts || {}, ...TOOLCHAIN_SCRIPTS };
  const deps = stripUninstallableDeps({ ...pkg.dependencies || {} });
  const devDeps = stripUninstallableDeps({ ...pkg.devDependencies || {} });
  for (const name of Object.keys(TOOLCHAIN_DEV_DEPENDENCIES)) {
    delete deps[name];
  }
  for (const name of CONFLICTING_TOOLCHAIN_PACKAGES) {
    delete deps[name];
    delete devDeps[name];
  }
  Object.assign(devDeps, TOOLCHAIN_DEV_DEPENDENCIES);
  const pair = resolvePinnedReactPair(deps);
  deps.react = pair.react;
  deps["react-dom"] = pair.reactDom;
  for (const [name, version] of Object.entries(RUNTIME_DEPENDENCY_PINS)) {
    if (Object.prototype.hasOwnProperty.call(deps, name)) {
      deps[name] = version;
    }
  }
  for (const [name, spec] of Object.entries(deps)) {
    if (String(spec || "").trim().toLowerCase() === "latest") {
      if (RUNTIME_DEPENDENCY_PINS[name]) {
        deps[name] = RUNTIME_DEPENDENCY_PINS[name];
      } else {
        delete deps[name];
      }
    }
  }
  pkg.dependencies = sortKeys(deps);
  pkg.devDependencies = sortKeys(devDeps);
  const changed = JSON.stringify(pkg) !== before;
  return { body: `${JSON.stringify(pkg, null, 2)}
`, changed };
}
function installSpecForPackage(name, pkg = null) {
  const key = String(name || "").trim();
  if (!key) return { skip: true };
  if (isUninstallableLabPackage(key)) return { skip: true };
  if (CONFLICTING_TOOLCHAIN_PACKAGES.includes(key)) return { skip: true };
  if (Object.prototype.hasOwnProperty.call(TOOLCHAIN_DEV_DEPENDENCIES, key)) {
    return { skip: false, version: TOOLCHAIN_DEV_DEPENDENCIES[key] };
  }
  const fromPkg = pkg?.devDependencies?.[key] ?? pkg?.dependencies?.[key];
  if (typeof fromPkg === "string" && fromPkg.trim() && fromPkg.trim() !== "latest") {
    return { skip: false, version: fromPkg.trim() };
  }
  return { skip: true };
}
function sortKeys(obj) {
  const sorted = {};
  for (const key of Object.keys(obj).sort()) {
    sorted[key] = obj[key];
  }
  return sorted;
}

// resources/js/lab/lib/vfs.js
function normalizeVfsPath(input = "") {
  return String(input).replace(/\\/g, "/").replace(/^\.\/+/, "").replace(/^\/+/, "").replace(/\/+/g, "/").replace(/\/\.$/, "").replace(/\/$/, "");
}
function relativeVfsImport(fromFile, toFile) {
  const from = normalizeVfsPath(fromFile);
  const to = normalizeVfsPath(toFile);
  const fromDir = from.includes("/") ? from.split("/").slice(0, -1) : [];
  const toParts = to.split("/").filter(Boolean);
  let i2 = 0;
  while (i2 < fromDir.length && i2 < toParts.length && fromDir[i2] === toParts[i2]) {
    i2 += 1;
  }
  const ups = fromDir.length - i2;
  const down = toParts.slice(i2);
  const rel = [...Array.from({ length: ups }, () => ".."), ...down].join("/");
  if (!rel) return "./";
  return rel.startsWith(".") ? rel : `./${rel}`;
}

// resources/js/lab/lib/placeholderPackages.js
var SOURCE_EXT = /\.(jsx?|tsx?|mjs|cjs)$/i;
var SHIM_DIR = "src/shims";
function shimUninstallableBareImports(files = {}, opts = {}) {
  const extra = [...opts?.extraPackages || []];
  const snapshot = files && typeof files === "object" ? files : {};
  const next = { ...snapshot };
  const byPkg = /* @__PURE__ */ new Map();
  for (const [path, body] of Object.entries(snapshot)) {
    if (!SOURCE_EXT.test(path)) continue;
    for (const row of extractBareImportRows(String(body || ""))) {
      const pkg = barePackageName(row.specifier);
      if (!pkg || !isUninstallableLabPackage(pkg, extra)) continue;
      let slot = byPkg.get(pkg);
      if (!slot) {
        slot = {
          named: /* @__PURE__ */ new Set(),
          wantDefault: false,
          namespace: false,
          specifiers: /* @__PURE__ */ new Set()
        };
        byPkg.set(pkg, slot);
      }
      slot.specifiers.add(row.specifier);
      if (row.kind === "default") slot.wantDefault = true;
      else if (row.kind === "namespace") slot.namespace = true;
      else if (row.kind === "named" && row.name) slot.named.add(row.name);
    }
  }
  if (!byPkg.size) {
    return { files: next, changed: false, shims: [] };
  }
  const shims = [];
  const shimByPkg = /* @__PURE__ */ new Map();
  for (const [pkg, slot] of byPkg) {
    const shimPath = `${SHIM_DIR}/${sanitizePackageFile(pkg)}.js`;
    shimByPkg.set(pkg, shimPath);
    next[shimPath] = buildPlaceholderShimSource(pkg, slot);
    shims.push(shimPath);
  }
  for (const [path, body] of Object.entries(snapshot)) {
    if (!SOURCE_EXT.test(path)) continue;
    let rewritten = String(body || "");
    for (const [pkg, slot] of byPkg) {
      const shimPath = shimByPkg.get(pkg);
      const rel = relativeVfsImport(path, shimPath);
      const specs = [...slot.specifiers].sort((a, b) => b.length - a.length);
      for (const spec of specs) {
        rewritten = replaceQuotedSpec(rewritten, spec, rel);
      }
    }
    if (rewritten !== body) next[path] = rewritten;
  }
  const changed = shims.some((path) => snapshot[path] !== next[path]) || Object.keys(next).some((path) => next[path] !== snapshot[path]);
  return { files: next, changed, shims };
}
function extractFailedNpmPackage(err2) {
  const text = String(err2?.message || err2 || "");
  const patterns = [
    /Failed to fetch package ["']([^"']+)["']/i,
    /Could not (?:find|fetch) package ["']([^"']+)["']/i,
    /404[^\n]*["'](@?[\w.-]+\/[\w.-]+|[\w.-]+)["']/i
  ];
  for (const re of patterns) {
    const match = text.match(re);
    if (match?.[1]) {
      return decodeURIComponent(String(match[1]).replace(/%2f/gi, "/")).trim();
    }
  }
  return "";
}
function extractBareImportRows(source = "") {
  const rows = [];
  const fromRe = /\b(?:import|export)\s+([\s\S]*?)\s+from\s+['"]([^'"]+)['"]/g;
  let match;
  while ((match = fromRe.exec(source)) !== null) {
    const clause = String(match[1] || "").trim();
    const spec = match[2];
    if (clause.startsWith("*")) {
      rows.push({ specifier: spec, kind: "namespace", name: "" });
      continue;
    }
    if (clause.startsWith("{")) {
      for (const name of namedFromClause(clause)) {
        rows.push({ specifier: spec, kind: "named", name });
      }
      continue;
    }
    rows.push({ specifier: spec, kind: "default", name: "" });
    const named = clause.match(/\{([^}]*)\}/);
    if (named) {
      for (const name of namedFromClause(`{${named[1]}}`)) {
        rows.push({ specifier: spec, kind: "named", name });
      }
    }
  }
  const sideRe = /\bimport\s*\(\s*['"]([^'"]+)['"]\s*\)|\brequire\s*\(\s*['"]([^'"]+)['"]\s*\)|\bimport\s+['"]([^'"]+)['"]/g;
  while ((match = sideRe.exec(source)) !== null) {
    const spec = match[1] || match[2] || match[3];
    if (!spec) continue;
    rows.push({ specifier: spec, kind: "namespace", name: "" });
  }
  return rows;
}
function namedFromClause(clause = "") {
  const inner = String(clause || "").replace(/^\{|\}$/g, "");
  return inner.split(",").map((part) => part.trim()).filter(Boolean).map((part) => {
    const alias = part.match(/^[A-Za-z_$][\w$]*\s+as\s+([A-Za-z_$][\w$]*)$/);
    if (alias) return alias[1];
    const id = part.match(/^([A-Za-z_$][\w$]*)$/);
    return id ? id[1] : "";
  }).filter(Boolean);
}
function replaceQuotedSpec(source, spec, next) {
  if (!spec || spec === next) return source;
  const escaped = spec.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
  const re = new RegExp(`(['"])${escaped}\\1`, "g");
  return source.replace(re, `$1${next}$1`);
}
function barePackageName(specifier = "") {
  const value = String(specifier || "").trim();
  if (!value || value.startsWith(".") || value.startsWith("/")) return "";
  if (/^(https?:|data:|blob:)/i.test(value)) return "";
  if (value.startsWith("@")) {
    const parts = value.split("/");
    return parts.length >= 2 ? `${parts[0]}/${parts[1]}` : value;
  }
  return value.split("/")[0] || "";
}
function sanitizePackageFile(name = "") {
  return String(name || "package").replace(/^@/, "").replace(/[^a-zA-Z0-9]+/g, "-").replace(/^-|-$/g, "").toLowerCase() || "package";
}
function buildPlaceholderShimSource(packageName, slot) {
  const named = [...slot?.named || []].filter((name) => /^[A-Za-z_$][\w$]*$/.test(name)).sort();
  const lines2 = [
    `/** Local stand-in for ${packageName} \u2014 not published to npm. */`,
    "function noop() { return null }"
  ];
  for (const name of named) {
    lines2.push(`export function ${name}() { return null }`);
  }
  if (slot?.wantDefault || slot?.namespace || named.length === 0) {
    const keys = named.length ? `${named.join(", ")}, default: noop` : "default: noop";
    lines2.push(`const __mod = { ${keys} }`);
    lines2.push("export default __mod");
  }
  return `${lines2.join("\n")}
`;
}

// resources/js/lab/lib/previewChromeBridge.js
var PREVIEW_CHROME_SOURCE = "krikkit-preview-chrome";
var PREVIEW_CHROME_REV = 49;
function createPreviewChromeScript(parentOrigin) {
  if (!/^https?:$/.test(new URL(parentOrigin).protocol) || new URL(parentOrigin).origin !== parentOrigin) {
    throw new Error("Preview bridge requires an exact HTTP(S) platform origin");
  }
  return `(function(){
  if (window.__krikkitPreviewChrome) return;
  window.__krikkitPreviewChrome = true;
  var SRC = ${JSON.stringify(PREVIEW_CHROME_SOURCE)};
  var REV = ${PREVIEW_CHROME_REV};
  var PARENT_ORIGIN = ${JSON.stringify(parentOrigin)};
  var binding = null;
  var pendingErrors = [];

  function postHost(payload) {
    if (!binding) {
      if (payload.type === 'guest-console-error') {
        pendingErrors.push(payload);
        if (pendingErrors.length > 20) pendingErrors.shift();
      }
      return false;
    }
    parent.postMessage(Object.assign({}, payload, {
      source: SRC, projectUuid: binding.projectUuid, token: binding.token
    }), PARENT_ORIGIN);
    return true;
  }

  function browserPath() {
    return (location.pathname || '/') + (location.search || '') + (location.hash || '');
  }

  /* MemoryRouter never touches window.location \u2014 keep a guest path for chrome. */
  var guestPath = browserPath();

  function currentPath() {
    return guestPath || browserPath();
  }

  function setGuestPath(path) {
    if (typeof path === 'string' && path) guestPath = path;
  }

  function report(kind, path) {
    if (path) setGuestPath(path);
    try {
      postHost({
        source: SRC,
        type: 'location',
        path: currentPath(),
        kind: kind || 'sync'
      });
    } catch (e) {}
  }

  var push = history.pushState;
  var repl = history.replaceState;
  history.pushState = function() {
    var r = push.apply(this, arguments);
    setGuestPath(browserPath());
    report('push');
    return r;
  };
  history.replaceState = function() {
    var r = repl.apply(this, arguments);
    setGuestPath(browserPath());
    report('replace');
    return r;
  };
  window.addEventListener('popstate', function() {
    setGuestPath(browserPath());
    report('pop');
  });
  window.addEventListener('hashchange', function() {
    setGuestPath(browserPath());
    report('hash');
  });

  function pathFromHref(href) {
    try {
      var url = new URL(href, location.href);
      if (url.origin !== location.origin) return null;
      return (url.pathname || '/') + (url.search || '') + (url.hash || '');
    } catch (e) {
      return null;
    }
  }

  document.addEventListener('click', function(ev) {
    var node = ev.target;
    while (node && node !== document && (!node.nodeName || node.nodeName !== 'A')) {
      node = node.parentNode;
    }
    if (!node || node.nodeName !== 'A') return;
    if (node.hasAttribute('download')) return;
    var tgt = node.getAttribute('target');
    if (tgt && tgt !== '_self') return;
    var raw = node.getAttribute('href');
    if (raw == null || raw === '') return;
    if (/^(mailto:|tel:|javascript:)/i.test(raw)) return;
    var next = pathFromHref(node.href);
    if (!next) return;
    setTimeout(function() {
      var active = document.querySelector('a[aria-current="page"]');
      if (active && active.href) {
        var fromNav = pathFromHref(active.href);
        if (fromNav) next = fromNav;
      }
      report('push', next);
    }, 0);
  }, true);

  /* \u2500\u2500 Inspect picker + context menu \u2500\u2500 */
  var inspecting = false;
  var contextPinned = false;
  var contextMenuOpen = false;
  var hoverEl = null;
  var selectedEl = null;
  var textEditing = false;
  var textEditEl = null;
  var textEditOriginal = '';
  var textEditRestore = null;
  var textEditLock = false;
  var box = null;
  var label = null;
  var raf = 0;
  var nextId = 1;
  var visible = false;
  var paintMode = 'morph';
  var EASE = 'cubic-bezier(0.22, 1, 0.36, 1)';
  var MORPH_MS = 200;
  var theme = {
    accent: '#14b8a6',
    surface: '#0a0a0a',
    fg: '#fafafa',
    line: 'rgba(255,255,255,0.12)'
  };

  function applyTheme(next) {
    if (!next || typeof next !== 'object') return;
    if (next.accent) theme.accent = String(next.accent);
    if (next.surface) theme.surface = String(next.surface);
    if (next.fg) theme.fg = String(next.fg);
    if (next.line) theme.line = String(next.line);
    if (box) {
      box.style.borderColor = theme.accent;
      box.style.background = 'color-mix(in oklab, ' + theme.accent + ' 12%, transparent)';
    }
    if (label) {
      label.style.background = theme.surface;
      label.style.color = theme.fg;
      label.style.borderColor = theme.line;
    }
  }

  function ensureOverlay() {
    if (box && label) return;
    var root = document.documentElement || document.body;
    box = document.createElement('div');
    box.setAttribute('data-krikkit-inspect', 'box');
    box.style.cssText = [
      'position:fixed',
      'inset:auto',
      'pointer-events:none',
      'z-index:2147483646',
      'box-sizing:border-box',
      'border:2px solid ' + theme.accent,
      'background:color-mix(in oklab, ' + theme.accent + ' 12%, transparent)',
      'border-radius:2px',
      'opacity:0',
      'transition:opacity 140ms ease'
    ].join(';');
    label = document.createElement('div');
    label.setAttribute('data-krikkit-inspect', 'label');
    label.style.cssText = [
      'position:fixed',
      'inset:auto',
      'pointer-events:none',
      'z-index:2147483647',
      'box-sizing:border-box',
      'max-width:min(70vw,28rem)',
      'padding:2px 6px',
      'border:1px solid ' + theme.line,
      'border-radius:2px',
      'background:' + theme.surface,
      'color:' + theme.fg,
      'font:11px/1.25 ui-sans-serif,system-ui,sans-serif',
      'white-space:nowrap',
      'overflow:hidden',
      'text-overflow:ellipsis',
      'opacity:0',
      'transition:opacity 140ms ease'
    ].join(';');
    root.appendChild(box);
    root.appendChild(label);
  }

  function isInspectChrome(el) {
    return !!(el && el.getAttribute && el.getAttribute('data-krikkit-inspect'));
  }

  function tagName(el) {
    return (el && el.tagName) ? el.tagName.toLowerCase() : '';
  }

  function hasOwnText(el) {
    if (!el) return false;
    for (var n = el.firstChild; n; n = n.nextSibling) {
      if (n.nodeType === 3 && String(n.textContent).replace(/\\s+/g, ' ').trim()) return true;
    }
    return false;
  }

  function ownText(el) {
    if (!el) return '';
    var parts = [];
    for (var n = el.firstChild; n; n = n.nextSibling) {
      if (n.nodeType === 3) {
        var t = String(n.textContent).replace(/\\s+/g, ' ').trim();
        if (t) parts.push(t);
      }
    }
    return parts.join(' ').trim();
  }

  function isWidget(el) {
    var t = tagName(el);
    if (t === 'img' || t === 'picture' || t === 'svg' || t === 'video' || t === 'audio' || t === 'canvas') return true;
    if (t === 'input' || t === 'textarea' || t === 'select' || t === 'button') return true;
    if (t === 'a') return true;
    var role = (el.getAttribute && el.getAttribute('role')) || '';
    return role === 'button' || role === 'link';
  }

  function isTextish(el) {
    return /^(h[1-6]|p|span|em|strong|small|label|li|figcaption|blockquote|td|th|a|button|legend)$/.test(tagName(el));
  }

  function isField(el) {
    var t = tagName(el);
    if (t === 'input' || t === 'textarea' || t === 'select') return true;
    return !!(el && el.isContentEditable);
  }

  function liftToWidget(el) {
    if (!el || isWidget(el)) return el;
    var p = el.parentElement;
    if (p && isWidget(p) && p !== document.body) {
      var t = tagName(el);
      if (t === 'span' || t === 'svg' || t === 'i' || t === 'path' || t === 'strong' || t === 'em') return p;
    }
    return el;
  }

  function hit(x, y) {
    var stack = [];
    try { stack = document.elementsFromPoint(x, y) || []; } catch (e) {
      var one = document.elementFromPoint(x, y);
      if (one) stack = [one];
    }
    var fallback = null;
    var textHit = null;
    for (var i = 0; i < stack.length; i++) {
      var el = stack[i];
      if (!el || el === document.documentElement || el === document.body) continue;
      if (isInspectChrome(el)) continue;
      if (el.nodeType !== 1) continue;
      if (!fallback) fallback = el;
      if (isWidget(el) || isField(el)) return liftToWidget(el);
      if (isTextish(el) && hasOwnText(el)) return liftToWidget(el);
      if (!textHit && hasOwnText(el)) textHit = el;
    }
    return liftToWidget(textHit || fallback);
  }

  function uidOf(el) {
    if (!el) return 0;
    if (!el.__krikkitInspectId) el.__krikkitInspectId = nextId++;
    return el.__krikkitInspectId;
  }

  function cssPath(el) {
    if (!el || el.nodeType !== 1) return '';
    var parts = [el.tagName.toLowerCase()];
    if (el.id) parts.push('#' + el.id);
    var cls = typeof el.className === 'string' ? el.className.trim() : '';
    if (cls) {
      var bits = cls.split(/\\s+/).filter(Boolean).slice(0, 3);
      if (bits.length) parts.push('.' + bits.join('.'));
    }
    return parts.join('');
  }

  function setMotion(morph) {
    if (!box || !label) return;
    if (morph) {
      var geo = 'left ' + MORPH_MS + 'ms ' + EASE +
        ', top ' + MORPH_MS + 'ms ' + EASE +
        ', width ' + MORPH_MS + 'ms ' + EASE +
        ', height ' + MORPH_MS + 'ms ' + EASE;
      box.style.transition = geo + ', opacity 140ms ease, background 140ms ease, border-color 140ms ease';
      label.style.transition = 'left ' + MORPH_MS + 'ms ' + EASE +
        ', top ' + MORPH_MS + 'ms ' + EASE +
        ', opacity 140ms ease';
    } else {
      box.style.transition = 'opacity 140ms ease, background 140ms ease, border-color 140ms ease';
      label.style.transition = 'opacity 140ms ease';
    }
  }

  function hideOverlay() {
    setMotion(false);
    if (box) box.style.opacity = '0';
    if (label) label.style.opacity = '0';
    visible = false;
  }

  function paintNow() {
    raf = 0;
    if (textEditing) {
      hideOverlay();
      return;
    }
    var mode = paintMode;
    paintMode = 'morph';
    var el = hoverEl || selectedEl;
    if (!el || !el.isConnected || el === document.documentElement || el === document.body) {
      hideOverlay();
      return;
    }
    ensureOverlay();
    var r = el.getBoundingClientRect();
    if (r.width <= 0 && r.height <= 0) {
      hideOverlay();
      return;
    }
    var morph = mode === 'morph' && visible;
    setMotion(morph);
    if (morph) void box.offsetWidth;

    var selected = el === selectedEl;
    box.style.left = r.left + 'px';
    box.style.top = r.top + 'px';
    box.style.width = r.width + 'px';
    box.style.height = r.height + 'px';
    box.style.borderColor = theme.accent;
    box.style.background = selected
      ? 'color-mix(in oklab, ' + theme.accent + ' 18%, transparent)'
      : 'color-mix(in oklab, ' + theme.accent + ' 10%, transparent)';
    box.style.opacity = '1';

    var text = cssPath(el) + '  ' + Math.round(r.width) + '\xD7' + Math.round(r.height);
    var ly = r.top > 22 ? r.top - 20 : r.top + r.height + 4;
    var lx = Math.min(Math.max(4, r.left), Math.max(4, window.innerWidth - 168));
    label.textContent = text;
    label.style.left = lx + 'px';
    label.style.top = Math.max(4, ly) + 'px';
    label.style.opacity = '1';
    visible = true;
  }

  function schedulePaint(mode) {
    if (mode === 'instant') paintMode = 'instant';
    else if (!raf) paintMode = mode || 'morph';
    if (raf) return;
    raf = window.requestAnimationFrame(paintNow);
  }

  function numPx(v) {
    if (v == null || v === '' || v === 'auto') return '';
    var n = parseFloat(v);
    return Number.isFinite(n) ? String(Math.round(n * 100) / 100) : String(v);
  }

  function visualRadius(cs, rect) {
    var raw = String((cs && cs.borderTopLeftRadius) || '0').trim().split(/\\s+/)[0] || '0';
    var n = parseFloat(raw);
    if (!Number.isFinite(n) || n < 0) n = 0;
    var w = Math.max(0, rect && rect.width || 0);
    var h = Math.max(0, rect && rect.height || 0);
    var cap = Math.min(w, h) / 2;
    if (raw.indexOf('%') !== -1) n = (n / 100) * (w || h);
    if (!Number.isFinite(cap)) cap = 0;
    if (n > cap) n = cap;
    return String(Math.round(n * 100) / 100);
  }

  function cssToHex(input) {
    var s = String(input || '').trim();
    if (!s || s === 'transparent' || s === 'none') return 'transparent';
    if (/^rgba?\\(\\s*0\\s*[,\\s]\\s*0\\s*[,\\s]\\s*0(?:\\s*[,/]\\s*0(?:\\.0+)?)?\\s*\\)$/i.test(s)) return 'transparent';
    if (/^#[0-9a-f]{6}$/i.test(s)) return s.toLowerCase();
    if (/^#[0-9a-f]{8}$/i.test(s)) {
      return s.slice(7).toLowerCase() === '00' ? 'transparent' : s.slice(0, 7).toLowerCase();
    }
    if (/^#[0-9a-f]{3}$/i.test(s)) {
      var t = s.slice(1);
      return ('#' + t[0] + t[0] + t[1] + t[1] + t[2] + t[2]).toLowerCase();
    }
    try {
      var canvas = document.createElement('canvas');
      canvas.width = 1;
      canvas.height = 1;
      var ctx = canvas.getContext('2d', { willReadFrequently: true });
      if (!ctx) return s;
      ctx.clearRect(0, 0, 1, 1);
      ctx.fillStyle = s;
      ctx.fillRect(0, 0, 1, 1);
      var d = ctx.getImageData(0, 0, 1, 1).data;
      if (!d[3]) return 'transparent';
      var hex = function(n) { return n.toString(16).padStart(2, '0'); };
      var out = '#' + hex(d[0]) + hex(d[1]) + hex(d[2]);
      if (d[3] < 255) {
        return 'rgba(' + d[0] + ', ' + d[1] + ', ' + d[2] + ', ' + (Math.round((d[3] / 255) * 100) / 100) + ')';
      }
      return out;
    } catch (e) {
      return s;
    }
  }

  function describe(el) {
    if (!el || el.nodeType !== 1) return null;
    var attrs = {};
    try {
      Array.prototype.forEach.call(el.attributes || [], function(a) {
        if (a && a.name) attrs[a.name] = String(a.value || '').slice(0, 200);
      });
    } catch (e) {}
    var r = el.getBoundingClientRect();
    var cs = getComputedStyle(el);
    var text = ownText(el);
    if (!text && isWidget(el)) {
      try {
        var inner = String(el.innerText || '').replace(/\\s+/g, ' ').trim();
        if (inner && inner.length <= 80 && el.children.length <= 3) text = inner.slice(0, 120);
      } catch (e2) {}
    }
    var opacityPct = Math.round((parseFloat(cs.opacity) || 1) * 100);
    return {
      uid: uidOf(el),
      tag: el.tagName.toLowerCase(),
      id: el.id || null,
      className: typeof el.className === 'string' ? el.className : '',
      path: cssPath(el),
      text: text,
      attributes: attrs,
      rect: {
        x: r.x, y: r.y, width: r.width, height: r.height,
        top: r.top, left: r.left, right: r.right, bottom: r.bottom
      },
      styles: {
        display: cs.display,
        position: cs.position,
        x: String(Math.round(el.offsetLeft || 0)),
        y: String(Math.round(el.offsetTop || 0)),
        w: String(Math.round(r.width)),
        h: String(Math.round(r.height)),
        opacity: String(opacityPct),
        borderRadius: visualRadius(cs, r),
        backgroundColor: cssToHex(cs.backgroundColor),
        color: cssToHex(cs.color),
        borderWidth: numPx(cs.borderTopWidth) || '0',
        borderColor: cssToHex(cs.borderTopColor),
        borderStyle: cs.borderTopStyle || 'none',
        fontSize: numPx(cs.fontSize),
        fontWeight: cs.fontWeight,
        lineHeight: cs.lineHeight === 'normal' ? 'normal' : numPx(cs.lineHeight),
        letterSpacing: cs.letterSpacing === 'normal' ? '0' : numPx(cs.letterSpacing),
        textAlign: cs.textAlign,
        paddingT: numPx(cs.paddingTop) || '0',
        paddingR: numPx(cs.paddingRight) || '0',
        paddingB: numPx(cs.paddingBottom) || '0',
        paddingL: numPx(cs.paddingLeft) || '0',
        marginT: numPx(cs.marginTop) || '0',
        marginR: numPx(cs.marginRight) || '0',
        marginB: numPx(cs.marginBottom) || '0',
        marginL: numPx(cs.marginLeft) || '0',
        flexDirection: cs.flexDirection,
        justifyContent: cs.justifyContent,
        alignItems: cs.alignItems,
        gap: numPx(cs.gap) || '0',
        overflow: cs.overflow
      }
    };
  }

  function emitSelect(el) {
    var data = describe(el);
    if (!data) return;
    try {
      postHost({ source: SRC, type: 'inspect-select', element: data });
    } catch (e) {}
  }

  function asPx(v) {
    if (v == null || v === '') return '';
    var s = String(v).trim();
    if (!s) return '';
    if (/[a-z%]/i.test(s)) return s;
    return s + 'px';
  }

  function applyText(el, text) {
    if (!el) return;
    var next = String(text == null ? '' : text);
    if (el.childElementCount === 0) {
      el.textContent = next;
      return;
    }
    var texts = [];
    for (var n = el.firstChild; n; n = n.nextSibling) {
      if (n.nodeType === 3 && String(n.textContent).trim()) texts.push(n);
    }
    if (texts.length === 1) {
      texts[0].textContent = next;
      return;
    }
    el.textContent = next;
  }

  function textEditTarget(el) {
    if (!el) return null;
    if (el.childElementCount === 0) return el;
    if (hasOwnText(el)) return el;
    var nodes = el.querySelectorAll('*');
    for (var i = 0; i < nodes.length; i++) {
      var n = nodes[i];
      if (hasOwnText(n) && n.childElementCount === 0) return n;
    }
    return el;
  }

  function readEditText(el) {
    if (!el) return '';
    var own = ownText(el);
    if (own) return own;
    try {
      return String(el.innerText || el.textContent || '').replace(/\\s+/g, ' ').trim();
    } catch (e) {
      return '';
    }
  }

  function restoreTextEditEl() {
    var el = textEditEl;
    var prev = textEditRestore;
    if (!el || !prev) return;
    try {
      el.removeEventListener('keydown', onTextEditKey, true);
      el.removeEventListener('blur', onTextEditBlur, true);
    } catch (e) {}
    try {
      if (prev.editable == null) el.removeAttribute('contenteditable');
      else el.setAttribute('contenteditable', prev.editable);
      el.style.outline = prev.outline;
      el.style.outlineOffset = prev.outlineOffset;
      el.style.cursor = prev.cursor;
      el.style.userSelect = prev.userSelect;
      el.style.minWidth = prev.minWidth;
      el.style.caretColor = prev.caretColor;
      el.style.padding = prev.padding;
      el.style.borderRadius = prev.borderRadius;
      el.style.boxShadow = prev.boxShadow;
      el.style.boxSizing = prev.boxSizing;
    } catch (e2) {}
  }

  function stopTextEditListeners() {
    document.removeEventListener('mousedown', onTextEditOutside, true);
    document.removeEventListener('click', onTextEditClick, true);
  }

  function onTextEditKey(ev) {
    if (!textEditing) return;
    if (ev.key === 'Escape') {
      ev.preventDefault();
      ev.stopPropagation();
      commitTextEdit();
      return;
    }
    if (ev.key === 'Enter' && !ev.shiftKey) {
      ev.preventDefault();
      ev.stopPropagation();
      commitTextEdit();
    }
  }

  function onTextEditBlur() {
    if (!textEditing) return;
    window.setTimeout(function() {
      if (textEditing) commitTextEdit();
    }, 0);
  }

  function onTextEditOutside(ev) {
    if (!textEditing || !textEditEl) return;
    if (textEditEl.contains(ev.target)) return;
    commitTextEdit();
  }

  function onTextEditClick(ev) {
    if (!textEditing || !textEditEl) return;
    if (!textEditEl.contains(ev.target)) return;
    var t = tagName(textEditEl);
    if (t === 'a' || t === 'button') {
      ev.preventDefault();
      ev.stopPropagation();
    }
  }

  function startTextEdit() {
    var host = selectedEl;
    if (!host || !host.isConnected) return;
    if (textEditing) commitTextEdit();
    var el = textEditTarget(host);
    if (!el || !el.isConnected) return;
    textEditing = true;
    textEditLock = false;
    textEditEl = el;
    selectedEl = host;
    textEditOriginal = readEditText(el);
    var r = el.getBoundingClientRect();
    var cs = getComputedStyle(el);
    var padT = (parseFloat(cs.paddingTop) || 0) + 3;
    var padR = (parseFloat(cs.paddingRight) || 0) + 6;
    var padB = (parseFloat(cs.paddingBottom) || 0) + 3;
    var padL = (parseFloat(cs.paddingLeft) || 0) + 6;
    textEditRestore = {
      editable: el.getAttribute('contenteditable'),
      outline: el.style.outline,
      outlineOffset: el.style.outlineOffset,
      cursor: el.style.cursor,
      userSelect: el.style.userSelect,
      minWidth: el.style.minWidth,
      caretColor: el.style.caretColor,
      padding: el.style.padding,
      borderRadius: el.style.borderRadius,
      boxShadow: el.style.boxShadow,
      boxSizing: el.style.boxSizing
    };
    hideOverlay();
    try {
      el.setAttribute('contenteditable', 'true');
      el.setAttribute('spellcheck', 'false');
      el.style.outline = 'none';
      el.style.outlineOffset = '0';
      el.style.cursor = 'text';
      el.style.userSelect = 'text';
      el.style.caretColor = theme.accent;
      el.style.boxSizing = 'content-box';
      el.style.padding = padT + 'px ' + padR + 'px ' + padB + 'px ' + padL + 'px';
      el.style.borderRadius = '8px';
      el.style.boxShadow = '0 0 0 1px ' + theme.accent;
      if (r.width > 0) el.style.minWidth = Math.ceil(r.width) + 'px';
      el.focus();
      var range = document.createRange();
      range.selectNodeContents(el);
      var sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(range);
    } catch (e) {}
    el.addEventListener('keydown', onTextEditKey, true);
    el.addEventListener('blur', onTextEditBlur, true);
    document.addEventListener('mousedown', onTextEditOutside, true);
    document.addEventListener('click', onTextEditClick, true);
  }

  function commitTextEdit() {
    if (!textEditing || textEditLock) return;
    textEditLock = true;
    var el = textEditEl;
    var host = selectedEl || el;
    var next = readEditText(el);
    var original = textEditOriginal;
    restoreTextEditEl();
    stopTextEditListeners();
    textEditing = false;
    textEditEl = null;
    textEditRestore = null;
    textEditOriginal = '';
    var data = describe(host);
    try {
      postHost({
        source: SRC,
        type: 'text-edit-commit',
        element: data,
        text: next,
        original: original
      });
    } catch (e) {}
    textEditLock = false;
  }

  function applyInspect(payload) {
    if (!selectedEl || !selectedEl.isConnected) return;
    var el = selectedEl;
    var st = payload && payload.styles ? payload.styles : {};
    try {
      if (st.w != null) el.style.width = asPx(st.w);
      if (st.h != null) el.style.height = asPx(st.h);
      if (st.x != null || st.y != null) {
        if (getComputedStyle(el).position === 'static') el.style.position = 'relative';
        if (st.x != null) el.style.left = asPx(st.x);
        if (st.y != null) el.style.top = asPx(st.y);
      }
      if (st.opacity != null) {
        var op = parseFloat(st.opacity);
        el.style.opacity = Number.isFinite(op)
          ? String(Math.max(0, Math.min(100, op)) / 100)
          : String(st.opacity);
      }
      if (st.borderRadius != null) el.style.borderRadius = asPx(st.borderRadius);
      if (st.backgroundColor != null) el.style.backgroundColor = String(st.backgroundColor);
      if (st.color != null) el.style.color = String(st.color);
      if (st.borderWidth != null) el.style.borderWidth = asPx(st.borderWidth);
      if (st.borderColor != null) el.style.borderColor = String(st.borderColor);
      if (st.borderStyle != null) el.style.borderStyle = String(st.borderStyle);
      if (st.fontSize != null) el.style.fontSize = asPx(st.fontSize);
      if (st.fontWeight != null) el.style.fontWeight = String(st.fontWeight);
      if (st.lineHeight != null) {
        el.style.lineHeight = String(st.lineHeight) === 'normal' ? 'normal' : asPx(st.lineHeight);
      }
      if (st.letterSpacing != null) el.style.letterSpacing = asPx(st.letterSpacing);
      if (st.textAlign != null) el.style.textAlign = String(st.textAlign);
      if (st.display != null) el.style.display = String(st.display);
      if (st.position != null) el.style.position = String(st.position);
      if (st.flexDirection != null) el.style.flexDirection = String(st.flexDirection);
      if (st.justifyContent != null) el.style.justifyContent = String(st.justifyContent);
      if (st.alignItems != null) el.style.alignItems = String(st.alignItems);
      if (st.gap != null) el.style.gap = asPx(st.gap);
      if (st.overflow != null) el.style.overflow = String(st.overflow);
      if (st.paddingT != null) el.style.paddingTop = asPx(st.paddingT);
      if (st.paddingR != null) el.style.paddingRight = asPx(st.paddingR);
      if (st.paddingB != null) el.style.paddingBottom = asPx(st.paddingB);
      if (st.paddingL != null) el.style.paddingLeft = asPx(st.paddingL);
      if (st.marginT != null) el.style.marginTop = asPx(st.marginT);
      if (st.marginR != null) el.style.marginRight = asPx(st.marginR);
      if (st.marginB != null) el.style.marginBottom = asPx(st.marginB);
      if (st.marginL != null) el.style.marginLeft = asPx(st.marginL);
      if (payload && payload.attrs && typeof payload.attrs === 'object') {
        Object.keys(payload.attrs).forEach(function(key) {
          var val = payload.attrs[key];
          if (val == null || val === false) el.removeAttribute(key);
          else el.setAttribute(key, String(val));
          if (key === 'value' && 'value' in el) el.value = String(val == null ? '' : val);
        });
      }
      if (payload && typeof payload.text === 'string') {
        applyText(el, payload.text);
      }
    } catch (e) {}
    schedulePaint('morph');
  }

  function onContext(ev) {
    if (ev.shiftKey) return;
    if (textEditing) {
      ev.preventDefault();
      commitTextEdit();
      return;
    }
    var raw = ev.target;
    var el = hit(ev.clientX, ev.clientY);
    if (isField(el) || isField(raw)) return;
    ev.preventDefault();
    ev.stopPropagation();
    if (typeof ev.stopImmediatePropagation === 'function') ev.stopImmediatePropagation();
    if (!el) return;
    selectedEl = el;
    var data = describe(el);
    if (!data) return;
    try {
      postHost({
        source: SRC,
        type: 'contextmenu',
        element: data,
        x: ev.clientX,
        y: ev.clientY
      });
    } catch (e) {}
    armContextDismiss();
  }

  function requestContextClose() {
    if (!contextMenuOpen) return;
    disarmContextDismiss();
    try {
      postHost({ source: SRC, type: 'context-close' });
    } catch (e) {}
  }

  function onContextOutside(ev) {
    if (!contextMenuOpen) return;
    if (ev.type === 'mousedown' && ev.button === 2) return;
    requestContextClose();
  }

  function onContextEscape(ev) {
    if (!contextMenuOpen || ev.key !== 'Escape') return;
    ev.preventDefault();
    requestContextClose();
  }

  function armContextDismiss() {
    if (contextMenuOpen) return;
    contextMenuOpen = true;
    document.addEventListener('mousedown', onContextOutside, true);
    document.addEventListener('click', onContextOutside, true);
    window.addEventListener('scroll', onContextOutside, true);
    window.addEventListener('keydown', onContextEscape, true);
  }

  function disarmContextDismiss() {
    if (!contextMenuOpen) return;
    contextMenuOpen = false;
    document.removeEventListener('mousedown', onContextOutside, true);
    document.removeEventListener('click', onContextOutside, true);
    window.removeEventListener('scroll', onContextOutside, true);
    window.removeEventListener('keydown', onContextEscape, true);
  }

  function onMove(ev) {
    if (textEditing || !inspecting) return;
    var el = hit(ev.clientX, ev.clientY);
    if (el === hoverEl) return;
    hoverEl = el;
    schedulePaint('morph');
  }

  function onClick(ev) {
    if (textEditing) return;
    if (!inspecting) return;
    ev.preventDefault();
    ev.stopPropagation();
    if (typeof ev.stopImmediatePropagation === 'function') ev.stopImmediatePropagation();
    var el = hit(ev.clientX, ev.clientY);
    if (!el) return;
    selectedEl = el;
    hoverEl = el;
    schedulePaint('morph');
    emitSelect(el);
  }

  function onScroll() {
    if (!inspecting) return;
    schedulePaint('instant');
  }

  function onKey(ev) {
    if (textEditing || !inspecting || ev.key !== 'Escape') return;
    ev.preventDefault();
    stopInspect();
    try {
      postHost({ source: SRC, type: 'inspect-cancel' });
    } catch (e) {}
  }

  function onLeave() {
    if (!inspecting || contextPinned) return;
    hoverEl = null;
    schedulePaint('morph');
  }

  function startInspect(nextTheme) {
    applyTheme(nextTheme);
    ensureOverlay();
    applyTheme(nextTheme);
    if (inspecting) {
      hoverEl = hoverEl || selectedEl;
      schedulePaint('instant');
      try { postHost({ source: SRC, type: 'inspect-state', active: true }); } catch (e) {}
      return;
    }
    inspecting = true;
    hoverEl = hoverEl || selectedEl;
    document.addEventListener('mousemove', onMove, true);
    document.addEventListener('click', onClick, true);
    document.addEventListener('keydown', onKey, true);
    document.documentElement.addEventListener('mouseleave', onLeave);
    window.addEventListener('scroll', onScroll, true);
    window.addEventListener('resize', onScroll);
    try {
      document.documentElement.style.cursor = 'crosshair';
    } catch (e) {}
    try { postHost({ source: SRC, type: 'inspect-state', active: true }); } catch (e) {}
    schedulePaint('instant');
  }

  function dismissContext() {
    contextPinned = false;
    disarmContextDismiss();
    if (!inspecting) {
      hoverEl = null;
      hideOverlay();
    }
  }

  function stopInspect() {
    if (!inspecting && !box) return;
    inspecting = false;
    hoverEl = null;
    selectedEl = null;
    if (raf) {
      window.cancelAnimationFrame(raf);
      raf = 0;
    }
    document.removeEventListener('mousemove', onMove, true);
    document.removeEventListener('click', onClick, true);
    document.removeEventListener('keydown', onKey, true);
    document.documentElement.removeEventListener('mouseleave', onLeave);
    window.removeEventListener('scroll', onScroll, true);
    window.removeEventListener('resize', onScroll);
    try {
      document.documentElement.style.cursor = '';
    } catch (e) {}
    hideOverlay();
    try { postHost({ source: SRC, type: 'inspect-state', active: false }); } catch (e) {}
  }

  var COVER_SKIP = { SCRIPT:1, NOSCRIPT:1, IFRAME:1, LINK:1, STYLE:1, META:1, TITLE:1, HEAD:1 };

  function coverAppRoot() {
    return document.getElementById('root') || document.getElementById('app') || document.body;
  }

  function coverPainted() {
    if (document.documentElement.getAttribute('data-krikkit-preview-boot') === '1') return false;
    if (document.body && document.body.getAttribute('data-krikkit-preview-boot') === '1') return false;
    if (document.querySelector('vite-error-overlay')) return true;
    var scripts = document.querySelectorAll('script[src]');
    var i;
    for (i = 0; i < scripts.length; i++) {
      var src = String(scripts[i].getAttribute('src') || scripts[i].src || '');
      if (src.indexOf('@vite/client') !== -1) return true;
    }
    var root = coverAppRoot();
    if (!root) return false;
    var text = String(root.innerText || '').replace(/\\s+/g, ' ').trim();
    var els = root.querySelectorAll ? root.querySelectorAll('*').length : 0;
    if ((root.id === 'root' || root.id === 'app') && root.childElementCount === 0) return false;
    if (text.length < 24 && els < 8) return false;
    return true;
  }

  function coverClassName(el) {
    var cls = el && el.className;
    if (!cls) return String((el && el.getAttribute && el.getAttribute('class')) || '');
    if (typeof cls === 'string') return cls;
    return String(cls.baseVal || el.getAttribute('class') || '');
  }

  function coverBoxVisible(el) {
    if (!el || !el.getBoundingClientRect) return false;
    var st;
    try { st = getComputedStyle(el); } catch (e) { return false; }
    if (st.display === 'none' || st.visibility === 'hidden' || Number(st.opacity) === 0) return false;
    var r = el.getBoundingClientRect();
    var vw = innerWidth || 0;
    var vh = innerHeight || 0;
    if (r.width < 6 || r.height < 6) return false;
    if (r.bottom < 0 || r.right < 0 || r.top > vh || r.left > vw) return false;
    return true;
  }

  function coverIsLoader(el) {
    if (!el || el.nodeType !== 1) return false;
    try {
      if (el.getAttribute('aria-busy') === 'true') return true;
      var role = String(el.getAttribute('role') || '').toLowerCase();
      if (role === 'progressbar') return true;
      var cls = coverClassName(el).toLowerCase();
      var id = String(el.id || '').toLowerCase();
      var label = String(el.getAttribute('aria-label') || '').toLowerCase();
      var hay = cls + ' ' + id + ' ' + label + ' ' + String(el.getAttribute('data-state') || '');
      if (/\\b(spinner|loader|loading|splash|skeleton|shimmer|preload|preloader)\\b/.test(hay)) return true;
      if (/\\banimate-spin\\b|\\banimate-pulse\\b/.test(cls)) return true;
      if (el.getAttribute('data-loading') != null || el.getAttribute('data-pending') != null) return true;
      var anim = '';
      try {
        var st = getComputedStyle(el);
        anim = String(st.animationName || '') + ' ' + String(st.webkitAnimationName || '');
      } catch (e2) {}
      if (/spin|loader|dash|skeleton|shimmer/i.test(anim) && anim.indexOf('none') === -1) return true;
    } catch (e) {}
    return false;
  }

  function coverIsCentered(el) {
    if (!el || !el.getBoundingClientRect) return false;
    var r = el.getBoundingClientRect();
    var vw = innerWidth || 1;
    var vh = innerHeight || 1;
    var cx = r.left + r.width / 2;
    var cy = r.top + r.height / 2;
    return Math.abs(cx - vw / 2) < vw * 0.28 && Math.abs(cy - vh / 2) < vh * 0.34;
  }

  function coverEachAnim(fn) {
    if (typeof document.getAnimations !== 'function') return;
    var list = document.getAnimations();
    var i;
    for (i = 0; i < list.length; i++) fn(list[i]);
  }

  function coverAnimTarget(a) {
    try { return a.effect && a.effect.target; } catch (e) { return null; }
  }

  function coverAnimInfinite(a) {
    try {
      var t = a.effect.getComputedTiming();
      return t.iterations === Infinity;
    } catch (e) { return false; }
  }

  function coverMotionPending() {
    var pending = false;
    coverEachAnim(function(a) {
      if (pending) return;
      if (a.playState !== 'running' && a.playState !== 'pending') return;
      var el = coverAnimTarget(a);
      if (el && !coverBoxVisible(el)) return;
      if (coverAnimInfinite(a)) {
        if (el && (coverIsLoader(el) || coverIsCentered(el))) pending = true;
        return;
      }
      if (!el) { pending = true; return; }
      var r = el.getBoundingClientRect();
      if (r.width * r.height > 6400 || coverIsCentered(el)) pending = true;
    });
    return pending;
  }

  function coverFreezeMotion() {
    var frozen = [];
    coverEachAnim(function(a) {
      try {
        if (!coverAnimInfinite(a)) {
          var t = a.effect.getComputedTiming();
          if (t && typeof t.endTime === 'number' && isFinite(t.endTime)) a.currentTime = t.endTime;
          else if (t && typeof t.duration === 'number' && isFinite(t.duration)) {
            a.currentTime = (t.delay || 0) + t.duration * (t.iterations || 1);
          }
        }
        a.pause();
        frozen.push(a);
      } catch (e) {}
    });
    return frozen;
  }

  function coverThawMotion(frozen) {
    var i;
    for (i = 0; i < frozen.length; i++) {
      try { frozen[i].play(); } catch (e) {}
    }
  }

  function coverStampForCapture() {
    var stamps = [];
    var root = coverAppRoot() || document.body;
    if (!root || !root.querySelectorAll) return stamps;
    var nodes = root.querySelectorAll('*');
    var limit = Math.min(nodes.length, 900);
    var i;
    for (i = 0; i < limit; i++) {
      var el = nodes[i];
      if (el.nodeType !== 1 || !coverBoxVisible(el)) continue;
      var st;
      try { st = getComputedStyle(el); } catch (e) { continue; }
      var anim = String(st.animationName || '');
      var trans = String(st.transitionDuration || '');
      var moving = (anim && anim !== 'none') || (trans && trans !== '0s' && trans.indexOf('0s') !== 0 && trans !== '0s, 0s');
      if (!moving && st.transform === 'none' && Number(st.opacity) === 1) continue;
      stamps.push({ el: el, css: el.getAttribute('style') });
      try {
        el.style.setProperty('animation', 'none', 'important');
        el.style.setProperty('transition', 'none', 'important');
        el.style.setProperty('opacity', st.opacity, 'important');
        el.style.setProperty('transform', st.transform, 'important');
        el.style.setProperty('filter', st.filter, 'important');
      } catch (e2) {}
    }
    return stamps;
  }

  function coverRestoreStamps(stamps) {
    var i;
    for (i = 0; i < stamps.length; i++) {
      try {
        if (stamps[i].css == null) stamps[i].el.removeAttribute('style');
        else stamps[i].el.setAttribute('style', stamps[i].css);
      } catch (e) {}
    }
  }

  function coverBusy() {
    if (document.readyState === 'loading') return true;
    try {
      if (document.fonts && document.fonts.status === 'loading') return true;
    } catch (e) {}
    var root = coverAppRoot() || document.body;
    if (!root) return true;
    try {
      if (document.documentElement.getAttribute('aria-busy') === 'true') return true;
      if (document.body && document.body.getAttribute('aria-busy') === 'true') return true;
    } catch (e2) {}
    var text = String(root.innerText || '').replace(/\\s+/g, ' ').trim().toLowerCase();
    if (text && text.length < 96 && /^(loading|please wait|starting)/.test(text)) return true;
    if (text && text.length < 56 && /(^|\\b)(loading|please wait)(\\b|$)/.test(text)) return true;
    var vw = innerWidth || 1;
    var vh = innerHeight || 1;
    var nodes = root.querySelectorAll('*');
    var limit = Math.min(nodes.length, 500);
    var i;
    for (i = 0; i < limit; i++) {
      var el = nodes[i];
      if (!coverIsLoader(el) || !coverBoxVisible(el)) continue;
      if (coverIsCentered(el)) return true;
      var r = el.getBoundingClientRect();
      if ((r.width * r.height) / (vw * vh) > 0.1) return true;
    }
    try {
      var imgs = document.images;
      for (i = 0; i < Math.min(imgs.length, 24); i++) {
        if (!imgs[i].complete && coverBoxVisible(imgs[i])) return true;
      }
    } catch (e3) {}
    return false;
  }

  function coverWaitReady(done) {
    done(coverPainted());
  }

  var coverPaintedSent = false;
  function coverNotifyPainted() {
    if (!binding || coverPaintedSent || !coverPainted()) return;
    coverPaintedSent = true;
    try { postHost({ source: SRC, type: 'cover-painted' }); } catch (e) {}
  }

  function coverColor(ctx, value, fallback) {
    var raw = String(value || '').trim();
    if (!raw || raw === 'transparent' || raw === 'none') return fallback;
    try {
      ctx.fillStyle = fallback || '#000';
      ctx.fillStyle = raw;
      return ctx.fillStyle || fallback;
    } catch (e) {
      return fallback;
    }
  }

  function coverTransparent(value) {
    var raw = String(value || '').trim().toLowerCase();
    if (!raw || raw === 'transparent' || raw === 'none') return true;
    return /rgba?\\(\\s*0\\s*,\\s*0\\s*,\\s*0\\s*,\\s*0/.test(raw) || raw.slice(-3) === ', 0)' || raw.slice(-2) === ',0)';
  }

  function coverSkipEl(el) {
    if (!el || !el.tagName) return true;
    if (COVER_SKIP[el.tagName]) return true;
    try {
      if (el.getAttribute && el.getAttribute('data-krikkit-inspect')) return true;
    } catch (e) {}
    return false;
  }

  function coverRadii(st, w, h, scale) {
    function px(raw) {
      var token = String(raw || '0').trim().split(' ')[0] || '0';
      var n = parseFloat(token) || 0;
      if (token.indexOf('%') !== -1) n = (n / 100) * Math.min(w / scale, h / scale);
      n = Math.max(0, n * scale);
      var cap = Math.min(w, h) / 2;
      return n > cap ? cap : n;
    }
    return [
      px(st.borderTopLeftRadius),
      px(st.borderTopRightRadius),
      px(st.borderBottomRightRadius),
      px(st.borderBottomLeftRadius)
    ];
  }

  function coverFillRound(ctx, x, y, w, h, radii) {
    ctx.beginPath();
    if (ctx.roundRect) ctx.roundRect(x, y, w, h, radii);
    else ctx.rect(x, y, w, h);
    ctx.fill();
  }

  function coverStrokeRound(ctx, x, y, w, h, radii) {
    ctx.beginPath();
    if (ctx.roundRect) ctx.roundRect(x, y, w, h, radii);
    else ctx.rect(x, y, w, h);
    ctx.stroke();
  }

  function coverIsSvgRoot(el) {
    if (!el || !el.tagName) return false;
    return String(el.tagName).toLowerCase() === 'svg';
  }

  function coverInSvg(el) {
    var node = el;
    while (node) {
      if (coverIsSvgRoot(node) && node !== el) return true;
      node = node.parentElement;
    }
    return false;
  }

  function coverCssFilter(st) {
    var f = String(st.filter || '');
    if (!f || f === 'none') f = String(st.webkitFilter || '');
    if (!f || f === 'none') return '';
    if (!/blur\\s*\\(\\s*(?:[1-9]\\d*(?:\\.\\d+)?|\\d*\\.[1-9]\\d*)px/i.test(f)) return '';
    return f;
  }

  function coverClipOverflow(ctx, el, scale) {
    var clips = [];
    var node = el && el.parentElement;
    while (node && node !== document.documentElement) {
      var st;
      try { st = getComputedStyle(node); } catch (e) { break; }
      var ox = String(st.overflowX || st.overflow || '');
      var oy = String(st.overflowY || st.overflow || '');
      if (ox === 'hidden' || oy === 'hidden' || ox === 'clip' || oy === 'clip') {
        clips.push({ node: node, st: st });
      }
      node = node.parentElement;
    }
    var i, r, x, y, w, h;
    for (i = clips.length - 1; i >= 0; i--) {
      r = clips[i].node.getBoundingClientRect();
      x = r.left * scale;
      y = r.top * scale;
      w = r.width * scale;
      h = r.height * scale;
      if (w < 1 || h < 1) continue;
      ctx.beginPath();
      if (ctx.roundRect) ctx.roundRect(x, y, w, h, coverRadii(clips[i].st, w, h, scale));
      else ctx.rect(x, y, w, h);
      ctx.clip();
    }
  }

  function coverBeginPaint(ctx, el, st, scale, withFilter) {
    ctx.save();
    coverClipOverflow(ctx, el, scale);
    if (withFilter) {
      var filt = coverCssFilter(st);
      if (filt) ctx.filter = filt;
    }
    var op = Number(st.opacity);
    if (!isNaN(op) && op < 1) ctx.globalAlpha = op;
  }

  function coverSkipTextParent(el) {
    if (!el || coverSkipEl(el) || coverInSvg(el) || coverIsSvgRoot(el)) return true;
    var tag = String(el.tagName || '').toUpperCase();
    return tag === 'SCRIPT' || tag === 'STYLE' || tag === 'NOSCRIPT' || tag === 'TEXTAREA' || tag === 'OPTION';
  }

  function coverTextVisuallyHidden(el, st) {
    if (st.visibility === 'hidden' || Number(st.opacity) === 0) return true;
    var indent = parseFloat(st.textIndent);
    if (!isNaN(indent) && indent <= -900) return true;
    var clip = String(st.clip || '');
    if (/rect\\s*\\(\\s*0px/i.test(clip)) return true;
    var clipPath = String(st.clipPath || st.webkitClipPath || '');
    if (clipPath.indexOf('inset(50%)') !== -1 || clipPath.indexOf('inset(100%)') !== -1) return true;
    var r = el.getBoundingClientRect();
    if ((st.overflow === 'hidden' || st.overflow === 'clip') && r.width <= 1 && r.height <= 1) return true;
    return false;
  }

  function coverUnionRect(a, b) {
    var left = Math.min(a.left, b.left);
    var top = Math.min(a.top, b.top);
    var right = Math.max(a.right, b.right);
    var bottom = Math.max(a.bottom, b.bottom);
    return { left: left, top: top, right: right, bottom: bottom, width: right - left, height: bottom - top };
  }

  function coverTextLines(node) {
    var text = String(node.textContent || '');
    if (!text || !text.replace(/\\s+/g, '').length) return [];
    var range = document.createRange();
    var lines = [];
    var current = null;
    var i, ch, r, top;
    for (i = 0; i < text.length; i++) {
      ch = text.charAt(i);
      if (ch === '\\n') {
        if (current) { lines.push(current); current = null; }
        continue;
      }
      try {
        range.setStart(node, i);
        range.setEnd(node, i + 1);
        r = range.getBoundingClientRect();
      } catch (e) {
        continue;
      }
      if (!r || (r.width <= 0 && r.height <= 0)) continue;
      top = Math.round(r.top);
      if (!current || Math.abs(current.top - top) > 3) {
        if (current) lines.push(current);
        current = { text: ch, rect: r, top: top };
      } else {
        current.text += ch;
        current.rect = coverUnionRect(current.rect, r);
      }
    }
    if (current) lines.push(current);
    return lines;
  }

  function coverPaintTexts(ctx, scale, vw, vh) {
    var walker;
    try {
      walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
    } catch (e) {
      return 0;
    }
    var hits = 0;
    var node, el, st, lines, li, line, r, fontSize, top, rh, spacing;
    while ((node = walker.nextNode())) {
      el = node.parentElement;
      if (coverSkipTextParent(el)) continue;
      try { st = getComputedStyle(el); } catch (e2) { continue; }
      if (st.display === 'none' || coverTextVisuallyHidden(el, st)) continue;
      lines = coverTextLines(node);
      if (!lines.length) continue;
      ctx.save();
      coverClipOverflow(ctx, el, scale);
      fontSize = Math.max(7, (parseFloat(st.fontSize) || 14) * scale);
      ctx.font = (st.fontStyle && st.fontStyle !== 'normal' ? st.fontStyle + ' ' : '') + (st.fontWeight || 400) + ' ' + fontSize + 'px ' + (st.fontFamily || 'sans-serif');
      ctx.fillStyle = coverColor(ctx, st.color, '#111111');
      ctx.textBaseline = 'top';
      ctx.textAlign = 'left';
      spacing = parseFloat(st.letterSpacing);
      if (ctx.letterSpacing !== undefined) {
        ctx.letterSpacing = (!isNaN(spacing) ? (spacing * scale) : 0) + 'px';
      }
      for (li = 0; li < lines.length; li++) {
        line = lines[li];
        r = line.rect;
        if (!r || r.width < 0.5 || r.height < 0.5) continue;
        if (r.bottom < 0 || r.right < 0 || r.top > vh || r.left > vw) continue;
        top = r.top * scale;
        rh = r.height * scale;
        ctx.fillText(line.text.replace(/\\n/g, ''), r.left * scale, top + Math.max(0, (rh - fontSize) / 2));
        hits++;
      }
      ctx.restore();
    }
    return hits;
  }

  function coverSvgImage(svg) {
    return new Promise(function(resolve) {
      try {
        var clone = svg.cloneNode(true);
        if (!clone.getAttribute('xmlns')) clone.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
        var st = getComputedStyle(svg);
        var color = st.color || '#111111';
        var rect = svg.getBoundingClientRect();
        var w = Math.max(1, Math.round(rect.width));
        var h = Math.max(1, Math.round(rect.height));
        clone.setAttribute('width', String(w));
        clone.setAttribute('height', String(h));
        if (!clone.getAttribute('viewBox')) {
          var vb = svg.getAttribute('viewBox');
          if (vb) clone.setAttribute('viewBox', vb);
          else clone.setAttribute('viewBox', '0 0 24 24');
        }
        var xml = new XMLSerializer().serializeToString(clone);
        xml = xml.replace(/currentColor/g, color);
        var img = new Image();
        var timer = setTimeout(function() { img.src = ''; resolve(null); }, 1500);
        img.onload = function() { clearTimeout(timer); resolve(img); };
        img.onerror = function() { clearTimeout(timer); resolve(null); };
        img.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(xml);
      } catch (e) {
        resolve(null);
      }
    });
  }

  function coverPaintPage() {
    var body = document.body;
    var root = document.documentElement;
    if (!body) return Promise.resolve(null);
    var vw = Math.max(1, Math.round(innerWidth || root.clientWidth || 0));
    var vh = Math.max(1, Math.round(innerHeight || root.clientHeight || 0));
    if (vw < 160 || vh < 90) return Promise.resolve(null);
    var scale = Math.min(1, 960 / vw);
    var canvas = document.createElement('canvas');
    canvas.width = Math.max(1, Math.round(vw * scale));
    canvas.height = Math.max(1, Math.round(vh * scale));
    var ctx = canvas.getContext('2d');
    if (!ctx) return Promise.resolve(null);
    var bodyBg = getComputedStyle(body).backgroundColor;
    var htmlBg = getComputedStyle(root).backgroundColor;
    ctx.fillStyle = coverColor(ctx, bodyBg, coverColor(ctx, htmlBg, '#ffffff'));
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    var nodes = body.querySelectorAll('*');
    var limit = Math.min(nodes.length, 1400);
    var hits = 0;
    var svgTasks = [];
    var i, el, st, r, x, y, w, h, bw;
    for (i = 0; i < limit; i++) {
      el = nodes[i];
      if (coverSkipEl(el) || coverInSvg(el) || coverIsSvgRoot(el)) continue;
      st = getComputedStyle(el);
      if (st.display === 'none' || st.visibility === 'hidden' || Number(st.opacity) === 0) continue;
      r = el.getBoundingClientRect();
      if (r.width < 1 || r.height < 1 || r.bottom < 0 || r.right < 0 || r.top > vh || r.left > vw) continue;
      x = r.left * scale;
      y = r.top * scale;
      w = r.width * scale;
      h = r.height * scale;
      coverBeginPaint(ctx, el, st, scale, true);
      if (!coverTransparent(st.backgroundColor)) {
        ctx.fillStyle = coverColor(ctx, st.backgroundColor, 'transparent');
        if (ctx.fillStyle && ctx.fillStyle !== 'transparent') {
          coverFillRound(ctx, x, y, w, h, coverRadii(st, w, h, scale));
          hits++;
        }
      }
      bw = parseFloat(st.borderTopWidth) || 0;
      if (bw > 0 && !coverTransparent(st.borderTopColor)) {
        ctx.strokeStyle = coverColor(ctx, st.borderTopColor, '#000');
        ctx.lineWidth = Math.max(1, bw * scale);
        coverStrokeRound(ctx, x, y, w, h, coverRadii(st, w, h, scale));
        hits++;
      }
      ctx.restore();
    }
    for (i = 0; i < limit; i++) {
      el = nodes[i];
      if (coverSkipEl(el) || coverInSvg(el)) continue;
      st = getComputedStyle(el);
      if (st.display === 'none' || st.visibility === 'hidden' || Number(st.opacity) === 0) continue;
      r = el.getBoundingClientRect();
      if (r.width < 1 || r.height < 1 || r.bottom < 0 || r.right < 0 || r.top > vh || r.left > vw) continue;
      x = r.left * scale;
      y = r.top * scale;
      w = r.width * scale;
      h = r.height * scale;
      if (coverIsSvgRoot(el)) {
        svgTasks.push({ el: el, x: x, y: y, w: w, h: h, st: st });
        continue;
      }
      if (el.tagName === 'IMG' && el.complete && el.naturalWidth > 0) {
        coverBeginPaint(ctx, el, st, scale, false);
        try { ctx.drawImage(el, x, y, w, h); hits++; } catch (e) {}
        ctx.restore();
      }
    }
    hits += coverPaintTexts(ctx, scale, vw, vh);
    if (!svgTasks.length) return Promise.resolve({ canvas: canvas, hits: hits });
    return Promise.all(svgTasks.map(function(task) {
      return coverSvgImage(task.el).then(function(img) {
        if (!img) return 0;
        coverBeginPaint(ctx, task.el, task.st, scale, false);
        try { ctx.drawImage(img, task.x, task.y, task.w, task.h); } catch (e) { ctx.restore(); return 0; }
        ctx.restore();
        return 1;
      });
    })).then(function(drawn) {
      var n = 0;
      var j;
      for (j = 0; j < drawn.length; j++) n += drawn[j];
      return { canvas: canvas, hits: hits + n };
    });
  }

  function coverInstallLib(source) {
    if (typeof window.html2canvas === 'function' || !source) return;
    try {
      var s = document.createElement('script');
      s.textContent = String(source);
      (document.head || document.documentElement).appendChild(s);
      s.remove();
    } catch (e) {}
    try {
      if (window.html2canvas && window.html2canvas.default) window.html2canvas = window.html2canvas.default;
    } catch (e2) {}
  }

  function coverFromCanvas(canvas) {
    if (!canvas || canvas.width < 32 || canvas.height < 32) return '';
    try { return canvas.toDataURL('image/jpeg', 0.84); } catch (e1) {
      try { return canvas.toDataURL('image/png'); } catch (e2) { return ''; }
    }
  }

  function coverSend(id, dataUrl) {
    try {
      postHost({ source: SRC, type: 'cover-result', id: id, ok: Boolean(dataUrl), dataUrl: dataUrl || '' });
    } catch (e) {}
  }

  function coverPaintFallback(id) {
    Promise.resolve()
      .then(function() { return coverPaintPage(); })
      .then(function(painted) {
        var canvas = painted && painted.canvas;
        if (!canvas || !painted.hits) {
          coverSend(id, '');
          return;
        }
        coverSend(id, coverFromCanvas(canvas));
      })
      .catch(function() {
        coverSend(id, '');
      });
  }

  function coverHtml2Canvas() {
    var fn = window.html2canvas;
    if (typeof fn !== 'function') return Promise.reject(new Error('missing'));
    var w = Math.max(1, Math.round(innerWidth || 0));
    var h = Math.max(1, Math.round(innerHeight || 0));
    if (w < 160 || h < 90) return Promise.reject(new Error('small'));
    return fn(document.documentElement, {
      width: w,
      height: h,
      windowWidth: w,
      windowHeight: h,
      x: Math.round(pageXOffset || 0),
      y: Math.round(pageYOffset || 0),
      scrollX: -(pageXOffset || 0),
      scrollY: -(pageYOffset || 0),
      scale: Math.min(1, 960 / w),
      useCORS: true,
      allowTaint: true,
      logging: false,
      backgroundColor: '#ffffff',
      foreignObjectRendering: false,
      imageTimeout: 2500,
      ignoreElements: function(el) {
        try { return !!(el && el.getAttribute && el.getAttribute('data-krikkit-inspect')); } catch (e) { return false; }
      }
    });
  }

  function coverWhenFontsReady() {
    try {
      if (document.fonts && document.fonts.ready) {
        return Promise.race([
          document.fonts.ready,
          new Promise(function(resolve) { setTimeout(resolve, 500); })
        ]);
      }
    } catch (e) {}
    return Promise.resolve();
  }

  function coverCapture(id) {
    coverWhenFontsReady()
      .then(function() { return coverPaintPage(); })
      .then(function(painted) {
        var canvas = painted && painted.canvas;
        if (!canvas) {
          coverSend(id, '');
          return;
        }
        coverSend(id, coverFromCanvas(canvas));
      })
      .catch(function() {
        coverSend(id, '');
      });
  }

  window.addEventListener('message', function(ev) {
    var d = ev && ev.data;
    if (ev.source !== parent || ev.origin !== PARENT_ORIGIN || !d || d.source !== SRC) return;
    if (d.type === 'bridge-connect') {
      if (typeof d.projectUuid !== 'string' || !d.projectUuid ||
          typeof d.token !== 'string' || !/^[a-f0-9-]{36}$/.test(d.token)) return;
      binding = { projectUuid: d.projectUuid, token: d.token };
      coverPaintedSent = false;
      report('init');
      coverNotifyPainted();
      var errors = pendingErrors;
      pendingErrors = [];
      errors.forEach(postHost);
      return;
    }
    if (!binding || d.projectUuid !== binding.projectUuid || d.token !== binding.token) return;
    try {
      if (d.type === 'preview-status') {
        var booting = document.documentElement.getAttribute('data-krikkit-preview-boot') === '1';
        var bodyText = String(document.body && document.body.innerText || '');
        var refused = /ERR_CONNECTION_REFUSED|connection refused|ERR_EMPTY_RESPONSE/i.test(bodyText);
        postHost({ type: 'preview-status', status: booting ? 'booting' : refused ? 'refused' : coverPainted() ? 'live' : 'waiting' });
        coverNotifyPainted();
        return;
      }
      if (d.type === 'cover-capture') {
        coverCapture(d.id);
        return;
      }
      if (d.type === 'navigate') {
        var next = d.path || '/';
        if (!next.startsWith('/')) next = '/' + next;
        var browser = location.pathname || '/';
        if (browser.length > 1 && browser.endsWith('/')) browser = browser.slice(0, -1);
        var logical = currentPath().split('?')[0].split('#')[0] || '/';
        if (logical.length > 1 && logical.endsWith('/')) logical = logical.slice(0, -1);
        if (browser !== next || logical !== next) {
          location.assign(next);
          return;
        }
        if (d.replace) history.replaceState(history.state, '', next);
        else history.pushState(history.state, '', next);
        dispatchEvent(new PopStateEvent('popstate', { state: history.state }));
        return;
      }
      if (d.type === 'back') { history.back(); return; }
      if (d.type === 'forward') { history.forward(); return; }
      if (d.type === 'reload') {
        postHost({ source: SRC, type: 'need-reload', path: currentPath() });
        return;
      }
      if (d.type === 'inspect-start') { startInspect(d.theme || null); return; }
      if (d.type === 'inspect-stop') { stopInspect(); return; }
      if (d.type === 'inspect-apply') applyInspect(d);
      if (d.type === 'context-dismiss') dismissContext();
      if (d.type === 'text-edit-start') {
        if (d.theme) applyTheme(d.theme);
        startTextEdit();
        return;
      }
    } catch (e) {}
  });

  document.addEventListener('contextmenu', onContext, true);

  try {
    var coverObs = new MutationObserver(coverNotifyPainted);
    coverObs.observe(document.documentElement, { subtree: true, childList: true, characterData: true });
  } catch (e) {}
  coverNotifyPainted();
  setTimeout(coverNotifyPainted, 600);
  setTimeout(coverNotifyPainted, 1600);
  setTimeout(coverNotifyPainted, 4000);

  /* \u2500\u2500 Guest runtime / console errors \u2192 Lab auto-fix \u2500\u2500 */
  var guestErrorSent = Object.create(null);
  var GUEST_ERROR_DEDUPE_MS = 5000;
  var guestBootUntil = Date.now() + 3500;

  function guestArgText(value) {
    if (value == null) return '';
    if (typeof value === 'string') return value;
    if (value instanceof Error) {
      return (value.message || 'Error') + (value.stack ? '\\n' + value.stack : '');
    }
    try { return JSON.stringify(value); } catch (e) { return String(value); }
  }

  function guestNoise(text) {
    var hay = String(text || '');
    if (!hay) return true;
    if (/websocket connection|hmr update|hmr connected|direct websocket connection fallback/i.test(hay)) return true;
    /* Vite chatter is noise \u2014 but vite ERRORS (internal server error, failed
       to reload, pre-transform) must reach the Fix card. */
    if (/\\[vite\\]/i.test(hay) && !/error|failed/i.test(hay)) return true;
    if (/failed to load resource/i.test(hay)) return true;
    if (/download the react devtools/i.test(hay)) return true;
    if (/^script error.?$/i.test(hay.trim())) return true;
    if (/^warning:/i.test(hay) && !/uncaught|typeerror|referenceerror|syntaxerror/i.test(hay)) return true;
    return false;
  }

  function guestComponentFromStack(text) {
    var m = String(text || '').match(/\\bat\\s+([A-Z][A-Za-z0-9_$]*)\\s*(?:\\(|$)/m);
    return m ? m[1] : null;
  }

  function guestFileFromStack(text) {
    var m = String(text || '').match(/((?:src|app|pages|components|lib|hooks)\\/[\\w./+-]+\\.[a-zA-Z0-9]+|[A-Za-z][\\w.-]*\\.(?:jsx|tsx|vue|svelte))(?::(\\d+))?/);
    if (!m) return { file: null, line: null };
    return { file: m[1] || null, line: m[2] ? Number(m[2]) : null };
  }

  function reportGuestError(payload) {
    var message = String(payload && payload.message || '').trim();
    var stack = String(payload && payload.stack || message || '').trim();
    if (!message || guestNoise(message) || guestNoise(stack)) return;
    if (Date.now() < guestBootUntil && (payload.category || 'RUNTIME_ERROR') !== 'BUILD_ERROR') return;
    var key = message.slice(0, 140);
    var now = Date.now();
    if (guestErrorSent[key] && now - guestErrorSent[key] < GUEST_ERROR_DEDUPE_MS) return;
    guestErrorSent[key] = now;
    var origin = guestFileFromStack(stack || message);
    try {
      postHost({
        source: SRC,
        type: 'guest-console-error',
        category: payload.category || 'RUNTIME_ERROR',
        message: message,
        stack: stack,
        file: payload.file || origin.file || null,
        line: payload.line != null ? payload.line : origin.line,
        component: payload.component || guestComponentFromStack(stack || message) || null
      });
    } catch (e) {}
  }

  /* Vite's build-error overlay renders inside a shadow root \u2014 surface it to
     the host so the Fix card appears without any manual reload. */
  var lastOverlayMsg = '';
  function checkViteOverlay() {
    try {
      var overlay = document.querySelector('vite-error-overlay');
      if (!overlay) { lastOverlayMsg = ''; return; }
      var root = overlay.shadowRoot;
      if (!root) return;
      var msgEl = root.querySelector('.message') || root.querySelector('.message-body');
      var fileEl = root.querySelector('.file');
      var msg = msgEl && msgEl.textContent ? msgEl.textContent.trim() : '';
      if (!msg || msg === lastOverlayMsg) return;
      lastOverlayMsg = msg;
      reportGuestError({
        category: 'BUILD_ERROR',
        message: msg,
        stack: msg,
        file: fileEl && fileEl.textContent ? fileEl.textContent.trim() : null
      });
    } catch (e) {}
  }
  setInterval(checkViteOverlay, 1200);

  window.addEventListener('error', function(ev) {
    if (!ev) return;
    var msg = String(ev.message || 'Script error');
    var stack = ev.error && ev.error.stack
      ? String(ev.error.stack)
      : msg + (ev.filename ? '\\n    at ' + ev.filename + (ev.lineno ? ':' + ev.lineno : '') : '');
    reportGuestError({
      message: msg,
      stack: stack,
      file: ev.filename || null,
      line: ev.lineno != null ? Number(ev.lineno) : null
    });
  }, true);

  window.addEventListener('unhandledrejection', function(ev) {
    var reason = ev && ev.reason;
    if (reason instanceof Error) {
      reportGuestError({ message: reason.message || 'Unhandled promise rejection', stack: reason.stack || reason.message || '' });
      return;
    }
    reportGuestError({ message: String(reason || 'Unhandled promise rejection'), stack: String(reason || '') });
  });

  var nativeConsoleError = console.error;
  console.error = function() {
    nativeConsoleError.apply(console, arguments);
    var parts = [];
    var i;
    for (i = 0; i < arguments.length; i++) parts.push(guestArgText(arguments[i]));
    var joined = parts.join('\\n').trim();
    if (!joined || guestNoise(joined)) return;
    var stack = joined;
    for (i = 0; i < arguments.length; i++) {
      if (arguments[i] instanceof Error && arguments[i].stack) {
        stack = String(arguments[i].stack);
        break;
      }
    }
    reportGuestError({ message: joined.split('\\n')[0], stack: stack });
  };

  report('init');
})();`;
}

// resources/js/lab/lib/previewReload.js
var PREVIEW_RESYNC_EVENT = "krikkit-preview-resync";
function guestWriteRestartsDev(path) {
  return /(?:^|\/)(?:package\.json|package-lock\.json|pnpm-lock\.yaml|yarn\.lock|vite\.config\.[cm]?[jt]s|postcss\.config\.[cm]?[jt]s|tailwind\.config\.[cm]?[jt]s|next\.config\.[cm]?[jt]s|index\.html)$/i.test(String(path || ""));
}
function guestWriteNeedsReload(path) {
  const p = String(path || "").replace(/\\/g, "/");
  if (guestWriteRestartsDev(p)) return true;
  if (/(?:^|\/)public\//i.test(p)) return true;
  if (/(?:^|\/)\.env(?:\..+)?$/i.test(p)) return true;
  if (/(?:^|\/)(?:ts|js)config(?:\.\w+)?\.json$/i.test(p)) return true;
  return false;
}

// resources/js/lab/lib/normalizeGuestRouter.js
var ROUTERS = ["MemoryRouter", "HashRouter", "BrowserRouter"];
var MEMORY_ROUTER_OPEN = `<MemoryRouter initialEntries={[(() => {
  try {
    let p = window.location.pathname || '/'
    if (p.length > 1 && p.endsWith('/')) p = p.slice(0, -1)
    return p || '/'
  } catch (e) {
    return '/'
  }
})()]} initialIndex={0}>`;
function isCustomRouterFile(path) {
  const base = String(path || "").replace(/^\/+/, "").split("/").pop() || "";
  return /^(MemoryRouter|HashRouter|BrowserRouter|Router)\.jsx$/i.test(base);
}
var GUEST_CUSTOM_ROUTER_REL_PATHS = [
  "src/components/MemoryRouter.jsx",
  "src/components/HashRouter.jsx",
  "src/components/BrowserRouter.jsx",
  "src/components/Router.jsx"
];
function stripCustomRouterImports(source = "") {
  let out = String(source || "");
  out = out.replace(
    /import\s+\{[^}]*\}\s+from\s+['"][^'"]*\/components\/(?:MemoryRouter|HashRouter|BrowserRouter|Router)(?:\.jsx)?['"];?\s*\n?/gi,
    ""
  );
  out = out.replace(
    /import\s+(?:MemoryRouter|HashRouter|BrowserRouter|Router)\s+from\s+['"][^'"]*\/components\/(?:MemoryRouter|HashRouter|BrowserRouter|Router)(?:\.jsx)?['"];?\s*\n?/gi,
    ""
  );
  return out;
}
function fixRouterDomImport(source = "", tag = "MemoryRouter") {
  let out = stripCustomRouterImports(source);
  if (new RegExp(`import\\s+\\{[^}]*\\b${tag}\\b[^}]*\\}\\s+from\\s+['"]react-router-dom['"]`).test(out)) {
    return out;
  }
  if (new RegExp(`import\\s+${tag}\\s+from\\s+['"][^'"]+['"]`).test(out)) {
    out = out.replace(
      new RegExp(`import\\s+${tag}\\s+from\\s+['"][^'"]+['"];?`, "g"),
      `import { ${tag} } from 'react-router-dom'`
    );
  }
  return out;
}
function purgeCustomRouterFiles(files = {}) {
  const out = { ...files };
  let changed = false;
  for (const key of Object.keys(out)) {
    if (!isCustomRouterFile(key)) continue;
    delete out[key];
    changed = true;
  }
  if (!changed) return { files: out, changed: false };
  for (const [key, body] of Object.entries(out)) {
    if (!/\.(jsx|tsx)$/i.test(key)) continue;
    const cleaned = stripCustomRouterImports(body);
    if (cleaned !== body) out[key] = cleaned;
  }
  return { files: out, changed: true };
}
function findKey(files, target) {
  const want = target.replace(/^\/+/, "");
  for (const key of Object.keys(files || {})) {
    if (String(key).replace(/^\/+/, "") === want) return key;
  }
  return null;
}
function routerInApp(source = "") {
  return ROUTERS.find((tag) => new RegExp(`<${tag}(\\s|>)`).test(String(source)));
}
function routerInMain(source = "") {
  return ROUTERS.find((tag) => new RegExp(`<${tag}(\\s|>)`).test(String(source)));
}
function stripRouterImport(source, tag) {
  return String(source).replace(
    new RegExp(`import\\s*\\{([^}]*)\\}\\s*from\\s*['"]react-router-dom['"];?`, "g"),
    (line, inner) => {
      const names = inner.split(",").map((part) => part.trim()).filter(Boolean).filter((name) => name !== tag && !name.startsWith(`${tag} `));
      if (!names.length) return "";
      return `import { ${names.join(", ")} } from 'react-router-dom'`;
    }
  );
}
function unwrapRouterFromApp(source, tag) {
  let app = String(source || "");
  const open = new RegExp(`<${tag}(\\s[^>]*)?>\\s*`, "m");
  const close = new RegExp(`\\s*</${tag}>`, "m");
  if (!open.test(app) || !close.test(app)) return null;
  app = app.replace(open, "");
  app = app.replace(close, "");
  app = stripRouterImport(app, tag);
  app = app.replace(/\n{3,}/g, "\n\n");
  return app;
}
function ensureRouterImport(main, tag) {
  let out = String(main || "");
  if (out.includes("react-router-dom")) {
    return out.replace(
      /import\s*\{([^}]*)\}\s*from\s*['"]react-router-dom['"];?/,
      (line, inner) => {
        const names = inner.split(",").map((part) => part.trim()).filter(Boolean);
        if (!names.includes(tag)) names.unshift(tag);
        return `import { ${names.join(", ")} } from 'react-router-dom'`;
      }
    );
  }
  const insertAt = out.indexOf("from 'react-dom/client'");
  if (insertAt >= 0) {
    const lineEnd = out.indexOf("\n", insertAt);
    return `${out.slice(0, lineEnd + 1)}import { ${tag} } from 'react-router-dom'
${out.slice(lineEnd + 1)}`;
  }
  return `import { ${tag} } from 'react-router-dom'
${out}`;
}
function wrapMainWithRouter(main, tag) {
  let out = ensureRouterImport(main, tag);
  if (routerInMain(out)) return out;
  const open = tag === "MemoryRouter" ? MEMORY_ROUTER_OPEN : `<${tag}>`;
  if (/<StrictMode>\s*\n?\s*<App\s*\/>/.test(out)) {
    return out.replace(
      /(<StrictMode>\s*\n?\s*)<App\s*\/>/,
      `$1${open}
      <App />
    </${tag}>`
    );
  }
  if (/<StrictMode>\s*\n?\s*<App>/.test(out)) {
    return out.replace(
      new RegExp(`(<StrictMode>\\s*\\n?\\s*)<App>([\\s\\S]*?</App>)`, "m"),
      `$1${open}
      <App>$2
    </${tag}>`
    );
  }
  return out;
}
function upgradeMemoryRouterMain(main) {
  let out = String(main || "");
  out = out.replace(/__krikkitGuestEntry\s*\(\)/g, "(() => { try { let p = window.location.pathname || '/'; if (p.length > 1 && p.endsWith('/')) p = p.slice(0, -1); return p || '/'; } catch (e) { return '/'; } })()");
  out = out.replace(/function __krikkitGuestEntry\(\)\s*\{[\s\S]*?\}\s*/g, "");
  if (out.includes("initialEntries") && out.includes("window.location.pathname")) {
    return out;
  }
  return out.replace(
    /<MemoryRouter(\s[^>]*)?>/,
    MEMORY_ROUTER_OPEN
  );
}
function normalizeGuestRouter(files = {}) {
  if (!files || typeof files !== "object") return files;
  const purged = purgeCustomRouterFiles(files);
  files = purged.files;
  const appKey = findKey(files, "src/App.jsx");
  const mainKey = findKey(files, "src/main.jsx");
  if (!appKey || !mainKey) return files;
  const appTag = routerInApp(files[appKey]);
  const mainTag = routerInMain(files[mainKey]);
  let app = stripCustomRouterImports(files[appKey]);
  let main = fixRouterDomImport(files[mainKey], "MemoryRouter");
  if (appTag) {
    const unwrapped = unwrapRouterFromApp(app, appTag);
    if (unwrapped) app = unwrapped;
  }
  if (mainTag) {
    if (mainTag === "MemoryRouter" && (!main.includes("initialEntries") || main.includes("__krikkitGuestEntry"))) {
      main = upgradeMemoryRouterMain(main);
    }
  } else if (appTag) {
    main = wrapMainWithRouter(main, appTag);
  }
  if (app === files[appKey] && main === files[mainKey] && !purged.changed) return files;
  return {
    ...files,
    [appKey]: app,
    [mainKey]: main
  };
}

// raw:D:\codea\krikkit-ai-website\resources\lab\site-kit\src\components\ui\button.jsx
var button_default = "import { Children, cloneElement, isValidElement } from 'react'\nimport { cva } from 'class-variance-authority'\nimport { cn } from '../../lib/utils'\n\nconst buttonVariants = cva(\n  'inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium transition duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/40 disabled:pointer-events-none disabled:opacity-50',\n  {\n    variants: {\n      variant: {\n        primary: 'bg-fg text-canvas hover:opacity-90',\n        accent: 'bg-accent text-white hover:opacity-90',\n        secondary: 'border border-line bg-surface text-fg hover:bg-soft',\n        outline: 'border border-line text-fg hover:border-fg/30 hover:bg-soft',\n        ghost: 'text-fg-muted hover:bg-soft hover:text-fg',\n      },\n      size: {\n        sm: 'h-8 px-3',\n        md: 'h-9 px-4',\n        lg: 'h-11 px-6 text-[15px]',\n      },\n    },\n    defaultVariants: {\n      variant: 'primary',\n      size: 'md',\n    },\n  },\n)\n\n/**\n * Minimal shadcn-style button primitive for Lab scaffolds.\n * Supports `asChild` so `<Button asChild><Link \u2026 /></Button>` merges classes\n * onto the child instead of nesting a button inside a link.\n */\nexport function Button({\n  className,\n  variant,\n  size,\n  type = 'button',\n  asChild = false,\n  children,\n  ...props\n}) {\n  const classes = cn(buttonVariants({ variant, size }), className)\n\n  if (asChild) {\n    const child = Children.only(children)\n    if (! isValidElement(child)) {\n      throw new Error('Button with asChild expects a single React element child.')\n    }\n    return cloneElement(child, {\n      ...props,\n      className: cn(classes, child.props.className),\n    })\n  }\n\n  return (\n    <button type={type} className={classes} {...props}>\n      {children}\n    </button>\n  )\n}\n\nexport { buttonVariants }\n\nexport default Button\n";

// resources/js/lab/lib/normalizeGuestUiKitCore.js
var BUTTON_REL = "src/components/ui/button.jsx";
function projectUsesButtonAsChild(files = {}) {
  for (const [path, body] of Object.entries(files)) {
    if (!/\.(jsx|tsx)$/i.test(String(path || ""))) continue;
    if (/<Button\b[^>]*\basChild\b/.test(String(body || ""))) return true;
  }
  return false;
}
function buttonNeedsAsChildPatch(source = "") {
  const body = String(source || "");
  if (!body.includes("Button")) return false;
  if (body.includes("asChild")) return false;
  return body.includes("...props") || body.includes("export function Button");
}
function normalizeGuestUiKit(files = {}, canonicalButton = "") {
  if (!files || typeof files !== "object") {
    return { files: files || {}, changed: false };
  }
  const usesAsChild = projectUsesButtonAsChild(files);
  if (!usesAsChild || !canonicalButton) {
    return { files, changed: false };
  }
  const key = Object.keys(files).find((path) => normalizeVfsPath(path) === BUTTON_REL);
  if (!key) {
    return { files: { ...files, [BUTTON_REL]: canonicalButton }, changed: true };
  }
  if (!buttonNeedsAsChildPatch(files[key])) {
    return { files, changed: false };
  }
  return {
    files: { ...files, [key]: canonicalButton },
    changed: true
  };
}

// resources/js/lab/lib/normalizeGuestUiKit.js
var CANONICAL_BUTTON = String(button_default || "");
function normalizeGuestUiKit2(files = {}, canonicalButton = CANONICAL_BUTTON) {
  return normalizeGuestUiKit(files, canonicalButton);
}

// resources/js/lab/lib/healUiImports.js
var CODE_FILE_RE = /\.(?:jsx?|tsx?)$/i;
var PROBE_EXTS = ["", ".jsx", ".tsx", ".js", ".ts", "/index.jsx", "/index.tsx", "/index.js", "/index.ts"];
var SPEC_RE = /((?:\bfrom|\bimport|\brequire)\s*\(?\s*)(['"])(\.{1,2}\/[^'"\n]+)\2/g;
var UI_TAIL_RE = /(?:^|\/)ui\/([\w$][\w$.-]*)$/;
var UI_HOME_DIR = "src/components/ui";
var BUTTON_PATH = `${UI_HOME_DIR}/button.jsx`;
function stripSlashes(path) {
  return String(path || "").replace(/^\/+/, "");
}
function dirOf(path) {
  const at = path.lastIndexOf("/");
  return at <= 0 ? "" : path.slice(0, at);
}
function resolveSpec(fromDir, spec) {
  const parts = fromDir ? fromDir.split("/") : [];
  for (const seg of String(spec).split("/")) {
    if (seg === "" || seg === ".") continue;
    if (seg === "..") {
      if (!parts.length) return null;
      parts.pop();
      continue;
    }
    parts.push(seg);
  }
  return parts.join("/");
}
function relativeSpec(fromDir, target) {
  const from = fromDir ? fromDir.split("/") : [];
  const to = target.split("/");
  let shared = 0;
  while (shared < from.length && shared < to.length && from[shared] === to[shared]) shared += 1;
  const ups = from.length - shared;
  const tail = to.slice(shared).join("/");
  return ups ? `${"../".repeat(ups)}${tail}` : `./${tail}`;
}
function healUiImports(files = {}, knownPaths = [], options = {}) {
  const untouched = { files: files || {}, patch: {}, changed: false, healed: [] };
  if (!files || typeof files !== "object") return untouched;
  const exists = /* @__PURE__ */ new Set();
  for (const path of Object.keys(files)) exists.add(stripSlashes(path));
  for (const path of knownPaths || []) exists.add(stripSlashes(path));
  const moduleExists = (base) => PROBE_EXTS.some((ext) => exists.has(base + ext));
  const canonicalButton = String(options.canonicalButton || "");
  let injectButton = false;
  const patch = {};
  const healed = [];
  for (const [key, body] of Object.entries(files)) {
    const rel = stripSlashes(key);
    if (typeof body !== "string" || !CODE_FILE_RE.test(rel) || rel.includes("node_modules/")) continue;
    const fromDir = dirOf(rel);
    let touched = false;
    const next = body.replace(SPEC_RE, (all, lead, quote, spec) => {
      const resolved = resolveSpec(fromDir, spec);
      if (!resolved || moduleExists(resolved)) return all;
      const tail = spec.match(UI_TAIL_RE);
      if (!tail) return all;
      const ext = tail[1].match(/\.(?:jsx?|tsx?)$/i)?.[0] || "";
      const name = ext ? tail[1].slice(0, -ext.length) : tail[1];
      const home = `${UI_HOME_DIR}/${name}`;
      let homeExists = moduleExists(home);
      if (!homeExists && name === "button" && canonicalButton) {
        injectButton = true;
        exists.add(BUTTON_PATH);
        homeExists = true;
      }
      if (!homeExists) return all;
      touched = true;
      return `${lead}${quote}${relativeSpec(fromDir, home)}${ext}${quote}`;
    });
    if (touched) {
      patch[key] = next;
      healed.push(key);
    }
  }
  if (!healed.length) return untouched;
  if (injectButton && !Object.keys(files).some((key) => stripSlashes(key) === BUTTON_PATH)) {
    patch[BUTTON_PATH] = canonicalButton;
  }
  return { files: { ...files, ...patch }, patch, changed: true, healed };
}

// resources/js/lab/lib/componentStub.js
var STUB_INTERACTIVE = /<(Button|Input|Label|Textarea|Link|img)\b/i;
function componentNameFromSource(source = "") {
  const match = String(source || "").match(/\bfunction\s+([A-Z]\w*)\s*\(/);
  return match?.[1] || "";
}
function isComponentNameStub(source = "") {
  const name = componentNameFromSource(source);
  if (!name) return false;
  const heading = new RegExp(`<h[12]\\b[^>]*>\\s*${name}\\s*<\\/h[12]>`);
  if (!heading.test(source)) return false;
  if (/\.map\s*\(/.test(source)) return false;
  if (STUB_INTERACTIVE.test(source)) return false;
  return String(source).length < 700;
}
function findDonorBody(stubName, contents) {
  const lower = stubName.toLowerCase();
  const candidates = [];
  if (/cta|band|closing/.test(lower)) {
    candidates.push("src/components/CtaBand.jsx", "src/components/ClosingCta.jsx");
  }
  if (/hero/.test(lower)) {
    candidates.push("src/components/Hero.jsx", "src/components/ServicesHero.jsx", "src/components/BookingHero.jsx");
  }
  for (const path of Object.keys(contents)) {
    if (/src\/components\/[A-Z]\w+\.jsx$/i.test(path)) {
      candidates.push(path);
    }
  }
  const seen = /* @__PURE__ */ new Set();
  for (const path of candidates) {
    if (seen.has(path)) continue;
    seen.add(path);
    const body = contents[path];
    if (!body || isComponentNameStub(body)) continue;
    if (body.length > 400) return { path, body };
  }
  return null;
}
function cloneDonorForStub(stubName, donorBody, donorName) {
  let next = donorBody;
  if (donorName) {
    next = next.replace(new RegExp(`\\bfunction\\s+${donorName}\\b`, "g"), `function ${stubName}`);
    next = next.replace(new RegExp(`export\\s+default\\s+${donorName}\\b`, "g"), `export default ${stubName}`);
    next = next.replace(new RegExp(`export\\s+{\\s*${donorName}\\s*}`, "g"), `export { ${stubName} }`);
  }
  return next;
}
function genericClosingSection(name) {
  return `import { Link } from 'react-router-dom'
import Button from './ui/button'
import Reveal from './ui/reveal'

export function ${name}(props) {
  return (
    <section className="relative overflow-hidden py-24 lg:py-32" {...props}>
      <div aria-hidden className="pointer-events-none absolute inset-x-0 bottom-0 h-96 bg-[radial-gradient(ellipse_at_bottom,color-mix(in_oklab,var(--color-accent)_12%,transparent),transparent_70%)]" />
      <div className="relative mx-auto max-w-4xl px-6 text-center">
        <Reveal>
          <h2 className="font-display text-4xl font-semibold tracking-tight lg:text-5xl">
            Ready to get started?
          </h2>
          <p className="mx-auto mt-6 max-w-2xl text-lg text-fg-muted">
            Book your appointment today and experience the difference for yourself.
          </p>
          <div className="mt-10">
            <Button asChild size="lg" variant="primary">
              <Link to="/booking">Book now</Link>
            </Button>
          </div>
        </Reveal>
      </div>
    </section>
  )
}

export default ${name}
`;
}
function healComponentStubs(contents = {}) {
  const patch = {};
  const healed = [];
  for (const [path, body] of Object.entries(contents)) {
    if (!/\.jsx$/i.test(path) || !isComponentNameStub(body)) continue;
    const stubName = componentNameFromSource(body);
    const donor = findDonorBody(stubName, contents);
    const donorName = donor ? componentNameFromSource(donor.body) : "";
    const next = donor ? cloneDonorForStub(stubName, donor.body, donorName) : genericClosingSection(stubName);
    patch[path] = next;
    healed.push(path);
  }
  return { patch, healed };
}
function applyComponentStubHeal(contents = {}) {
  const { patch } = healComponentStubs(contents);
  if (!Object.keys(patch).length) return contents;
  return { ...contents, ...patch };
}

// resources/js/lab/lib/labRuntime.js
var engine = null;
var readyPromise = null;
var sessionGeneration = 0;
var previewPort = null;
var previewUrl2 = null;
var portListeners = /* @__PURE__ */ new Set();
var guestSyncedContent = /* @__PURE__ */ new Map();
var previewResyncTimer = 0;
function schedulePreviewResync(reason = "guest", delayMs = 400) {
  if (typeof window === "undefined") return;
  window.clearTimeout(previewResyncTimer);
  previewResyncTimer = window.setTimeout(() => {
    try {
      window.dispatchEvent(new CustomEvent(PREVIEW_RESYNC_EVENT, {
        detail: { reason, at: Date.now() }
      }));
    } catch {
    }
  }, Math.max(0, Number(delayMs) || 0));
}
var SKIP_SYNC_RE = /(?:^|\/)(?:node_modules|\.next|\.git|dist|build|coverage)(?:\/|$)/i;
async function purgeGuestCustomRouterFiles(pod = engine) {
  const target = pod || engine;
  if (!target?.fs?.rm) return 0;
  let removed = 0;
  for (const rel of GUEST_CUSTOM_ROUTER_REL_PATHS) {
    for (const path of [rel, `/${rel}`]) {
      try {
        await target.fs.rm(path, { force: true });
        guestSyncedContent.delete(path);
        guestSyncedContent.delete(path.replace(/^\//, ""));
        removed += 1;
      } catch {
      }
    }
  }
  return removed;
}
function maybePatchNextConfigForLab(path, source) {
  if (!/\/next\.config\.(js|mjs|cjs|ts)$/i.test(path)) return source;
  let out = source;
  if (!/allowedDevOrigins\s*:/.test(out)) {
    const origins = `['127.0.0.1', 'localhost']`;
    if (/export\s+default\s*\{/.test(out)) {
      out = out.replace(
        /export\s+default\s*\{/,
        `export default {
  allowedDevOrigins: ${origins},`
      );
    } else if (/module\.exports\s*=\s*\{/.test(out)) {
      out = out.replace(
        /module\.exports\s*=\s*\{/,
        `module.exports = {
  allowedDevOrigins: ${origins},`
      );
    } else if (/=\s*\{/.test(out) && /export\s+default\s+\w+/.test(out)) {
      out = out.replace(
        /=\s*\{/,
        `= {
  allowedDevOrigins: ${origins},`
      );
    }
  }
  if (!/__krikkitLabWebpack/.test(out)) {
    out = out.replace(
      /webpack:\s*\(config\)\s*=>\s*\{\s*config\.cache\s*=\s*false;\s*return config\s*\}/,
      `webpack: ${LAB_WEBPACK_FN}`
    );
  }
  if (!/__krikkitLabWebpack/.test(out)) {
    const exported = out.match(/export\s+default\s+([A-Za-z_$][\w$]*)/);
    const name = exported && exported[1];
    if (name && name !== "function") {
      out += `
;(() => { const __c = ${name}; if (!__c || typeof __c !== 'object' || (__c.webpack && __c.webpack.__krikkitLab)) return; const __prev = __c.webpack; const __lab = ${LAB_WEBPACK_FN}; const __wrap = function __krikkitLabWebpack(config, ctx) { if (typeof __prev === 'function') { const n = __prev(config, ctx); if (n) config = n; } return __lab(config, ctx); }; __wrap.__krikkitLab = true; __c.webpack = __wrap; })();
`;
    } else if (/export\s+default\s*\{/.test(out)) {
      out = out.replace(
        /export\s+default\s*\{/,
        `export default {
  webpack: ${LAB_WEBPACK_FN},`
      );
    } else if (/module\.exports\s*=\s*\{/.test(out)) {
      out = out.replace(
        /module\.exports\s*=\s*\{/,
        `module.exports = {
  webpack: ${LAB_WEBPACK_FN},`
      );
    }
  }
  return out;
}
function maybePatchViteConfigForLab(path, source) {
  if (!/\/vite\.config\.(js|mjs|cjs|ts)$/i.test(path)) return source;
  if (/__krikkitLabWatch/.test(source)) return source;
  const wrap = `function __krikkitLabWatch(config) {
  if (!config || typeof config !== 'object') return config;
  var server = Object.assign({}, config.server || {});
  var watch = Object.assign({}, server.watch || {});
  var extra = ['**/tmp/**', '**/home/**', '**/.krikkit_history', '**/.deepthought_history'];
  var prev = watch.ignored;
  watch.ignored = !prev ? extra : Array.isArray(prev) ? prev.concat(extra) : [prev].concat(extra);
  server.watch = watch;
  return Object.assign({}, config, { server: server });
}
function __krikkitLabWatchWrap(raw) {
  if (typeof raw === 'function') {
    return function (env) {
      var out = raw(env);
      return out && typeof out.then === 'function' ? out.then(__krikkitLabWatch) : __krikkitLabWatch(out);
    };
  }
  return __krikkitLabWatch(raw);
}
`;
  if (/export\s+default\s+/.test(source)) {
    return wrap + source.replace(/export\s+default\s+/, "export default __krikkitLabWatchWrap(") + "\n)\n";
  }
  if (/module\.exports\s*=/.test(source)) {
    return wrap + source.replace(/module\.exports\s*=/, "module.exports = __krikkitLabWatchWrap(") + "\n)\n";
  }
  return source;
}
function maybePatchGuestConfig(path, source) {
  return maybePatchViteConfigForLab(path, maybePatchNextConfigForLab(path, source));
}
var LAB_WEBPACK_FN = `function __krikkitLabWebpack(config, ctx) {
  if (!config || typeof config !== 'object') return config;
  config.cache = false;
  if (ctx && ctx.isServer) return config;
  try {
    config.plugins = (config.plugins || []).filter(function (p) {
      var n = p && p.constructor && p.constructor.name;
      return n !== 'MiniCssExtractPlugin' && n !== 'CssExtractRspackPlugin';
    });
    var isExtract = function (l) {
      if (!l) return false;
      var s = typeof l === 'string' ? l : String((l && l.loader) || '');
      return /mini-css-extract|css-extract-rspack/i.test(s);
    };
    var styleLoader = {
      loader: 'next-style-loader',
      options: {
        insert: function (element) {
          var a = typeof document !== 'undefined' && document.querySelector('#__next_css__DO_NOT_USE__');
          if (a && a.parentNode) a.parentNode.insertBefore(element, a);
          else if (typeof document !== 'undefined') (document.head || document.documentElement).appendChild(element);
        }
      }
    };
    var swap = function (use) {
      if (!use) return use;
      if (Array.isArray(use)) return use.map(swap);
      if (isExtract(use)) return styleLoader;
      return use;
    };
    var walk = function (rules) {
      if (!rules) return;
      for (var i = 0; i < rules.length; i++) {
        var rule = rules[i];
        if (!rule) continue;
        if (rule.use) rule.use = swap(rule.use);
        if (rule.loader && isExtract(rule)) {
          rule.loader = styleLoader.loader;
          rule.options = styleLoader.options;
        }
        if (rule.oneOf) walk(rule.oneOf);
        if (rule.rules) walk(rule.rules);
      }
    };
    if (config.module) walk(config.module.rules);
  } catch (e) {}
  return config;
}`;
function shouldSkipGuestSyncPath(path) {
  return SKIP_SYNC_RE.test(path);
}
function vfsContentsToFiles(contents, knownPaths = []) {
  const relative = {};
  if (contents && typeof contents === "object") {
    for (const [raw, body] of Object.entries(contents)) {
      if (body == null) continue;
      relative[normalizeVfsPath(raw)] = typeof body === "string" ? body : String(body);
    }
  }
  if (relative["package.json"]) {
    relative["package.json"] = normalizeLabPackageJson(relative["package.json"]).body;
  }
  const routed = normalizeGuestRouter(relative);
  const uiKit = normalizeGuestUiKit2(routed);
  const imports = healUiImports(uiKit.files, knownPaths, { canonicalButton: CANONICAL_BUTTON });
  const healed = shimUninstallableBareImports(imports.files);
  const files = {};
  for (const [raw, body] of Object.entries(healed.files)) {
    const path = raw.startsWith("/") ? raw : `/${raw}`;
    files[path] = maybePatchGuestConfig(path, body);
  }
  return files;
}
function getLabRuntime() {
  return engine;
}
function signalGuestListening(port, url) {
  const p = Number(port) | 0;
  if (!p) return;
  previewPort = p;
  if (url) previewUrl2 = url;
  else if (engine?.port) {
    try {
      previewUrl2 = engine.port(p) || null;
    } catch {
      previewUrl2 = null;
    }
  }
  for (const fn of portListeners) {
    try {
      fn({ type: "open", port: p, url: previewUrl2, source: "signal" });
    } catch {
    }
  }
  try {
    window.dispatchEvent(
      new CustomEvent("krikkit-lab-runtime-listen", {
        detail: { port: p, url: previewUrl2 }
      })
    );
  } catch {
  }
}
function ensureLabSession(opts = {}) {
  if (engine) return Promise.resolve(engine);
  if (readyPromise) return readyPromise;
  const generation = sessionGeneration;
  readyPromise = (async () => {
    await removePlatformRuntimeWorkers();
    const files = vfsContentsToFiles(opts.files);
    const workdir = opts.workdir || "/";
    const pod = await bootGuestRuntime({
      files,
      workdir,
      // Bare `cd` / `~` follow $HOME. Engine default is /home/user (empty mock).
      env: {
        HOME: workdir,
        PWD: workdir,
        // In-memory history only — a VFS HISTFILE is watched by Vite (`page reload`).
        HISTFILE: "/dev/null"
      },
      onServerReady: (port, url) => {
        signalGuestListening(port, url);
      }
    });
    if (generation !== sessionGeneration) {
      await pod.teardown();
      throw new Error("Runtime project changed during startup.");
    }
    engine = pod;
    for (const stale of [
      "/tmp/.krikkit_history",
      "/.krikkit-lab_history",
      "/.deepthought_history",
      "/home/user/.krikkit-lab_history",
      "/home/user/.deepthought_history"
    ]) {
      try {
        await pod.fs.rm?.(stale);
      } catch {
      }
    }
    try {
      await pod.setPreviewScript(createPreviewChromeScript(window.location.origin));
    } catch {
    }
    return pod;
  })().catch((err2) => {
    if (generation === sessionGeneration) {
      readyPromise = null;
      engine = null;
    }
    throw err2;
  });
  return readyPromise;
}
async function destroyLabSession() {
  sessionGeneration += 1;
  const pod = engine;
  engine = null;
  readyPromise = null;
  previewPort = null;
  previewUrl2 = null;
  guestSyncedContent.clear();
  if (!pod) return;
  try {
    await pod.teardown?.();
  } catch {
  }
}
async function syncLabVfsToGuest(patch, options = {}) {
  const force = Boolean(options.force);
  const empty = { wrote: 0, needsRestart: false, needsReload: false };
  const pod = engine;
  if (!pod?.fs?.writeFile) return empty;
  await purgeGuestCustomRouterFiles(pod);
  const healedPatch = applyComponentStubHeal(patch);
  const files = vfsContentsToFiles(healedPatch, guestSyncedContent.keys());
  if (projectUsesButtonAsChild(patch) && CANONICAL_BUTTON) {
    files["/src/components/ui/button.jsx"] = CANONICAL_BUTTON;
  }
  let needsReload = false;
  let needsRestart = false;
  let wrote = 0;
  for (const [path, raw] of Object.entries(files)) {
    if (shouldSkipGuestSyncPath(path)) continue;
    const rel = path.replace(/^\/+/, "");
    if (isCustomRouterFile(rel)) continue;
    const data = maybePatchGuestConfig(path, raw);
    const cached = guestSyncedContent.get(path) === data;
    const sensitive = guestWriteRestartsDev(path) || guestWriteNeedsReload(path);
    try {
      let guestMatches = cached;
      if (!guestMatches) {
        try {
          const existing = await pod.fs.readFile(path, "utf8");
          const text = typeof existing === "string" ? existing : String(existing ?? "");
          guestMatches = text === data;
          if (guestMatches) guestSyncedContent.set(path, data);
        } catch {
          guestMatches = false;
        }
      }
      if (guestMatches && (!force || sensitive)) {
        continue;
      }
      const dir = path.slice(0, path.lastIndexOf("/")) || "/";
      if (dir && dir !== "/") {
        try {
          await pod.fs.mkdir(dir, { recursive: true });
        } catch {
        }
      }
      await pod.fs.writeFile(path, data);
      guestSyncedContent.set(path, data);
      wrote += 1;
      if (guestWriteRestartsDev(path)) needsRestart = true;
      else if (guestWriteNeedsReload(path)) needsReload = true;
    } catch (err2) {
      console.warn("[lab] fs.writeFile failed", path, err2);
    }
  }
  if (!needsRestart && needsReload) {
    schedulePreviewResync("guest-reload", 400);
  }
  return { wrote, needsRestart, needsReload };
}

// resources/js/lab/lib/guestBuildErrors.js
var BUILD_ERROR_LINE = new RegExp([
  "\\[vite\\][^\\n]*(?:internal server error|failed to load|error when evaluating)",
  "pre-transform error",
  "\\[plugin:[^\\]]+\\]",
  "failed to resolve import",
  "failed to compile",
  "module not found",
  "transform failed with",
  "\u2718 \\[ERROR\\]",
  "rolluperror",
  "\\b(?:syntaxerror|referenceerror|typeerror)\\b(?=.*(?:src/|app/|pages/|components/))"
].join("|"), "i");

// resources/js/lab/lib/labConfig.js
function readLabConfig() {
  try {
    const el = document.getElementById("lab-config");
    if (el?.textContent) {
      return JSON.parse(el.textContent) || {};
    }
  } catch {
  }
  return {};
}

// resources/js/lab/lib/labConsole.js
function bootConsole() {
  const raw = readLabConfig().console;
  return raw && typeof raw === "object" ? raw : {};
}
function labConsoleName() {
  const name = String(bootConsole().name || "").trim();
  return name || "Krikkit Lab";
}

// resources/js/lab/lib/labNativeWasm.js
var JSDELIVR = "https://cdn.jsdelivr.net/npm";
var MIN_WASM_BYTES = 64;
var MIN_GLUE_BYTES = 8;
var COMPILER_WASM_COMPANIONS = [
  {
    parent: "lightningcss",
    packageName: "lightningcss-wasm",
    wasmFiles: ["lightningcss_node.wasm"],
    glueFiles: [
      "package.json",
      "wasm-node.cjs",
      "index.cjs",
      "node_modules/napi-wasm/package.json",
      "node_modules/napi-wasm/index.js"
    ]
  },
  {
    parent: "@tailwindcss/oxide",
    packageName: "@tailwindcss/oxide-wasm32-wasi",
    wasmFiles: ["tailwindcss-oxide.wasm32-wasi.wasm"],
    glueFiles: [
      "package.json",
      "tailwindcss-oxide.wasi.cjs",
      "wasi-worker.mjs"
    ]
  }
];
function isNativeWasmPackageName(pkgName) {
  const name = String(pkgName || "");
  return name.endsWith("-wasm") || name.includes("wasm32-wasi") || name.includes("-wasm32-");
}
function wasmPackageDir(packageName) {
  return `/node_modules/${String(packageName || "").replace(/^\/+/, "")}`;
}
function compilerWasmSpecsFromParents(parentVersions) {
  const versions = parentVersions && typeof parentVersions === "object" ? parentVersions : {};
  const specs = [];
  for (const companion of COMPILER_WASM_COMPANIONS) {
    const version = String(versions[companion.parent] || "").trim();
    if (!version) continue;
    specs.push({ ...companion, version });
  }
  return specs;
}
function defaultWasmFilesForPackage(pkgName) {
  const name = String(pkgName || "");
  const companion = COMPILER_WASM_COMPANIONS.find((item) => item.packageName === name);
  return companion ? companion.wasmFiles.slice() : [];
}
function wasmAssetsFromPackageListing(fileNames) {
  const names = Array.isArray(fileNames) ? fileNames.map(String) : [];
  const assets = /* @__PURE__ */ new Set();
  for (const name of names) {
    if (/\.debug\.wasm$/i.test(name)) continue;
    if (/\.wasm$/i.test(name)) {
      assets.add(name);
      continue;
    }
    const stem = name.replace(/\.(js|cjs|mjs)$/i, "");
    if (stem === name) continue;
    if (/wasi-worker/i.test(stem)) continue;
    if (/lightningcss_node$/i.test(stem) || /wasm32-wasi$/i.test(stem)) {
      assets.add(`${stem}.wasm`);
    }
  }
  return [...assets];
}
function wasmAliasPaths(pkgName, fileName) {
  const aliases = [];
  if (pkgName === "lightningcss-wasm") {
    aliases.push(`/lightningcss-wasm/${fileName}`);
  }
  return aliases;
}
function packageNameFromDir(dir) {
  const stripped = String(dir || "").replace(/\/+$/, "").replace(/^\/node_modules\//, "");
  const nested = stripped.lastIndexOf("/node_modules/");
  return nested === -1 ? stripped : stripped.slice(nested + "/node_modules/".length);
}
async function readdirSafe(pod, dir) {
  try {
    const entries = await pod.fs.readdir(dir);
    return Array.isArray(entries) ? entries.map(String) : [];
  } catch {
    return [];
  }
}
async function readJsonSafe(pod, path) {
  try {
    const raw = await pod.fs.readFile(path, "utf8");
    return JSON.parse(typeof raw === "string" ? raw : String(raw));
  } catch {
    return null;
  }
}
async function fileMissing(pod, path, minBytes = MIN_GLUE_BYTES) {
  try {
    const st = await pod.fs.stat(path);
    return !st || Number(st.size) < minBytes;
  } catch {
    return true;
  }
}
async function wasmMissing(pod, path) {
  return fileMissing(pod, path, MIN_WASM_BYTES);
}
async function listGuestWasmPackageDirs(pod) {
  const dirs = [];
  const seen = /* @__PURE__ */ new Set();
  const push = (dir) => {
    const key = String(dir || "").replace(/\/+$/, "") || "/";
    if (!key.startsWith("/node_modules/")) return;
    if (seen.has(key)) return;
    seen.add(key);
    dirs.push(key);
  };
  const top = await readdirSafe(pod, "/node_modules");
  for (const name of top) {
    if (name.startsWith("@")) {
      const scoped = await readdirSafe(pod, `/node_modules/${name}`);
      for (const child of scoped) {
        const dir = `/node_modules/${name}/${child}`;
        if (isNativeWasmPackageName(`${name}/${child}`)) push(dir);
        const nestedTop = await readdirSafe(pod, `${dir}/node_modules`);
        for (const nested of nestedTop) {
          if (nested.startsWith("@")) {
            const nestedScoped = await readdirSafe(pod, `${dir}/node_modules/${nested}`);
            for (const nestedChild of nestedScoped) {
              if (isNativeWasmPackageName(`${nested}/${nestedChild}`)) {
                push(`${dir}/node_modules/${nested}/${nestedChild}`);
              }
            }
          } else if (isNativeWasmPackageName(nested)) {
            push(`${dir}/node_modules/${nested}`);
          }
        }
      }
      continue;
    }
    if (isNativeWasmPackageName(name)) push(`/node_modules/${name}`);
  }
  return dirs;
}
async function readCompilerParentVersions(pod) {
  const versions = {};
  for (const companion of COMPILER_WASM_COMPANIONS) {
    const pkg = await readJsonSafe(pod, `${wasmPackageDir(companion.parent)}/package.json`);
    const version = String(pkg?.version || "").trim();
    if (version) versions[companion.parent] = version;
  }
  return versions;
}
async function fetchCdnBytes(url, opts = {}) {
  if (typeof fetch !== "function") return null;
  try {
    const resp = await fetch(url);
    if (!resp.ok) return null;
    const buf = new Uint8Array(await resp.arrayBuffer());
    if (buf.byteLength < (opts.wasm ? MIN_WASM_BYTES : MIN_GLUE_BYTES)) return null;
    if (opts.wasm && (buf[0] !== 0 || buf[1] !== 97 || buf[2] !== 115 || buf[3] !== 109)) {
      return null;
    }
    return buf;
  } catch {
    return null;
  }
}
async function fetchWasmBytes(url) {
  return fetchCdnBytes(url, { wasm: true });
}
async function writeBytes(pod, path, bytes) {
  const dir = path.slice(0, path.lastIndexOf("/")) || "/";
  try {
    await pod.fs.mkdir?.(dir, { recursive: true });
  } catch {
  }
  await pod.fs.writeFile(path, bytes);
}
async function compilerCompanionReady(pod, spec) {
  const dir = wasmPackageDir(spec.packageName);
  if (await fileMissing(pod, `${dir}/package.json`)) return false;
  const files = spec.wasmFiles.length ? spec.wasmFiles : defaultWasmFilesForPackage(spec.packageName);
  if (!files.length) return false;
  for (const fileName of files) {
    if (await wasmMissing(pod, `${dir}/${fileName}`)) return false;
  }
  return true;
}
async function installCompilerCompanion(pod, spec) {
  const installer = pod.packages;
  if (typeof installer?.install !== "function") return false;
  try {
    await installer.install(spec.packageName, spec.version, {
      withDevDeps: false,
      transformModules: false
    });
    return true;
  } catch {
    return false;
  }
}
async function seedCompanionGlue(pod, spec) {
  let seeded = 0;
  const dir = wasmPackageDir(spec.packageName);
  const glue = Array.isArray(spec.glueFiles) ? spec.glueFiles : [];
  for (const fileName of glue) {
    const dest = `${dir}/${fileName}`;
    if (!await fileMissing(pod, dest)) continue;
    const bytes = await fetchCdnBytes(
      `${JSDELIVR}/${spec.packageName}@${spec.version}/${fileName}`
    );
    if (!bytes) continue;
    await writeBytes(pod, dest, bytes);
    seeded += 1;
  }
  return seeded;
}
async function seedWasmAssets(pod, dir, pkgName, version, assets) {
  let seeded = 0;
  for (const fileName of assets) {
    const dest = `${dir}/${fileName}`;
    const wantDebug = /wasm32-wasi/i.test(fileName) || /wasm32-wasi/i.test(pkgName);
    const debugDest = dest.replace(/\.wasm$/i, ".debug.wasm");
    const needRelease = await wasmMissing(pod, dest);
    const needDebug = wantDebug && await wasmMissing(pod, debugDest);
    if (!needRelease && !needDebug) {
      for (const alias of wasmAliasPaths(pkgName, fileName)) {
        if (await wasmMissing(pod, alias)) {
          try {
            const existing = await pod.fs.readFile(dest);
            await writeBytes(pod, alias, existing);
            seeded += 1;
          } catch {
          }
        }
      }
      continue;
    }
    const cdnFile = fileName.replace(/\.debug\.wasm$/i, ".wasm");
    const bytes = await fetchWasmBytes(
      `${JSDELIVR}/${pkgName}@${version}/${cdnFile}`
    );
    if (!bytes) continue;
    if (needRelease) {
      await writeBytes(pod, dest, bytes);
      seeded += 1;
    }
    if (needDebug) {
      await writeBytes(pod, debugDest, bytes);
      seeded += 1;
    }
    for (const alias of wasmAliasPaths(pkgName, fileName)) {
      if (await wasmMissing(pod, alias)) {
        await writeBytes(pod, alias, bytes);
        seeded += 1;
      }
    }
  }
  return seeded;
}
async function seedGuestNativeWasm(pod) {
  if (!pod?.fs?.writeFile || !pod.fs.readdir) return 0;
  const specs = compilerWasmSpecsFromParents(await readCompilerParentVersions(pod));
  let seeded = 0;
  for (const spec of specs) {
    if (await compilerCompanionReady(pod, spec)) continue;
    if (await installCompilerCompanion(pod, spec)) {
      seeded += 1;
    }
    seeded += await seedCompanionGlue(pod, spec);
  }
  const dirs = new Set(await listGuestWasmPackageDirs(pod));
  for (const spec of specs) {
    dirs.add(wasmPackageDir(spec.packageName));
  }
  for (const dir of dirs) {
    const pkg = await readJsonSafe(pod, `${dir}/package.json`);
    const pkgName = String(pkg?.name || packageNameFromDir(dir));
    const spec = specs.find((item) => item.packageName === pkgName);
    const version = String(pkg?.version || spec?.version || "latest");
    const files = await readdirSafe(pod, dir);
    const assets = new Set(wasmAssetsFromPackageListing(files));
    for (const fileName of defaultWasmFilesForPackage(pkgName)) {
      assets.add(fileName);
    }
    if (!assets.size) continue;
    seeded += await seedWasmAssets(pod, dir, pkgName, version, [...assets]);
  }
  return seeded;
}

// resources/js/lab/lib/labAutostart.js
var runningDevProc = null;
var outputTerminal = null;
var LAB_RUNTIME_PHASE = "krikkit-lab-phase";
function publishLabPhase(phase, detail = {}) {
  if (typeof window === "undefined") return;
  try {
    window.dispatchEvent(new CustomEvent(LAB_RUNTIME_PHASE, {
      detail: { phase, ...detail, at: Date.now() }
    }));
  } catch {
  }
}
async function pathExists(pod, path) {
  try {
    await pod.fs.stat(path);
    return true;
  } catch {
    return false;
  }
}
async function readPackageJson(pod) {
  try {
    const raw = await pod.fs.readFile("/package.json", "utf8");
    return JSON.parse(typeof raw === "string" ? raw : String(raw));
  } catch {
    return null;
  }
}
async function hasInstalledModules(pod) {
  try {
    const entries = await pod.fs.readdir("/node_modules");
    if (!Array.isArray(entries) || entries.length === 0) return false;
    return entries.some((name) => name && name !== ".bin" && name !== ".package-lock.json");
  } catch {
    return false;
  }
}
var VITE_CONFIG_FILES = [
  "/vite.config.js",
  "/vite.config.ts",
  "/vite.config.mjs",
  "/vite.config.cjs"
];
var SKIP_VITE_IMPORTS = /* @__PURE__ */ new Set([
  "vite",
  "fs",
  "path",
  "url",
  "os",
  "module",
  "process",
  "node:fs",
  "node:path",
  "node:url",
  "node:module"
]);
function barePackageNameFromSpecifier(spec) {
  const value = String(spec || "").trim();
  if (!value || value.startsWith(".") || value.startsWith("/") || value.startsWith("node:")) {
    return null;
  }
  if (value.startsWith("@")) {
    const parts = value.split("/");
    if (parts.length < 2 || !parts[1]) return null;
    return `${parts[0]}/${parts[1]}`;
  }
  const name = value.split("/")[0];
  return name || null;
}
function bareImportsFromViteConfig(source) {
  const text = String(source || "");
  const names = /* @__PURE__ */ new Set();
  const patterns = [
    /\bfrom\s*['"]([^'"]+)['"]/g,
    /\bimport\s*\(\s*['"]([^'"]+)['"]\s*\)/g,
    /\brequire\s*\(\s*['"]([^'"]+)['"]\s*\)/g
  ];
  for (const re of patterns) {
    let match;
    while (match = re.exec(text)) {
      const name = barePackageNameFromSpecifier(match[1]);
      if (name && !SKIP_VITE_IMPORTS.has(name)) names.add(name);
    }
  }
  return [...names];
}
async function packageLooksInstalled(pod, name) {
  const root = `/node_modules/${name}`;
  if (!await pathExists(pod, `${root}/package.json`)) return false;
  try {
    const raw = await pod.fs.readFile(`${root}/package.json`, "utf8");
    const mf = JSON.parse(typeof raw === "string" ? raw : String(raw));
    const exportsRoot = mf?.exports && typeof mf.exports === "object" ? mf.exports["."] : mf?.exports;
    const fromExports = typeof exportsRoot === "string" ? exportsRoot : exportsRoot && typeof exportsRoot === "object" ? exportsRoot.default || exportsRoot.import || exportsRoot.require : null;
    const rel = String(mf.module || mf.main || fromExports || "").replace(/^\.\//, "");
    if (rel && await pathExists(pod, `${root}/${rel}`)) return true;
  } catch {
  }
  for (const rel of ["dist/index.mjs", "dist/index.js", "index.mjs", "index.js", "index.cjs"]) {
    if (await pathExists(pod, `${root}/${rel}`)) return true;
  }
  return false;
}
async function missingViteConfigPackages(pod) {
  const names = /* @__PURE__ */ new Set();
  for (const file of VITE_CONFIG_FILES) {
    try {
      const raw = await pod.fs.readFile(file, "utf8");
      for (const name of bareImportsFromViteConfig(typeof raw === "string" ? raw : String(raw))) {
        names.add(name);
      }
    } catch {
    }
  }
  const missing = [];
  for (const name of names) {
    if (!await packageLooksInstalled(pod, name)) missing.push(name);
  }
  return missing;
}
async function viteMajorVersion(pod) {
  try {
    const raw = await pod.fs.readFile("/node_modules/vite/package.json", "utf8");
    const version = JSON.parse(typeof raw === "string" ? raw : String(raw))?.version;
    const major = parseInt(String(version || "").split(".")[0], 10);
    return Number.isFinite(major) ? major : 0;
  } catch {
    return 0;
  }
}
function termLine(terminal, line, err2 = false) {
  const sink = outputTerminal || terminal;
  const text = err2 ? `\x1B[31m${line}\x1B[0m` : line;
  if (sink?.writeln) sink.writeln(text);
  else if (sink?.write) sink.write(`${text}\r
`);
}
function sleep(ms, signal) {
  return new Promise((resolve, reject) => {
    if (signal?.aborted) {
      reject(new DOMException("Aborted", "AbortError"));
      return;
    }
    const id = setTimeout(resolve, ms);
    signal?.addEventListener("abort", () => {
      clearTimeout(id);
      reject(new DOMException("Aborted", "AbortError"));
    }, { once: true });
  });
}
async function waitForPackageJson(pod, opts = {}) {
  const timeoutMs = opts.timeoutMs ?? 25e3;
  const start = Date.now();
  let lastSync = 0;
  while (Date.now() - start < timeoutMs) {
    if (opts.signal?.aborted) return null;
    const now = Date.now();
    if (typeof opts.getFiles === "function" && now - lastSync > 400) {
      lastSync = now;
      try {
        const files = opts.getFiles() || {};
        if (Object.keys(files).length) {
          await syncLabVfsToGuest(files);
        }
      } catch {
      }
    }
    const pkg = await readPackageJson(pod);
    if (pkg) return pkg;
    try {
      await sleep(250, opts.signal);
    } catch {
      return null;
    }
  }
  return readPackageJson(pod);
}
async function pinGuestPackageJson(pod, pkg) {
  if (!pkg || typeof pkg !== "object") return pkg;
  const { body, changed } = normalizeLabPackageJson(JSON.stringify(pkg));
  if (!changed) return pkg;
  try {
    await pod.fs.writeFile("/package.json", body);
  } catch {
    return pkg;
  }
  try {
    return JSON.parse(body);
  } catch {
    return pkg;
  }
}
async function installManifestSkippingUnpublished(args) {
  const { pod, installer, flags, terminal, getFiles, signal, note: note2 } = args;
  let pkg = args.pkg;
  const skipped = [];
  for (let attempt = 0; attempt < 8; attempt++) {
    if (signal?.aborted) return pkg;
    try {
      if (installer.installFromManifest) {
        await installer.installFromManifest("/package.json", flags);
      } else {
        await installer.install(void 0, void 0, flags);
      }
      return pkg;
    } catch (err2) {
      const name = extractFailedNpmPackage(err2);
      if (!name || skipped.includes(name)) throw err2;
      skipped.push(name);
      const line = `${labConsoleName()} \xB7 skipping unpublished package ${name}`;
      termLine(terminal, `\x1B[90m${line}\x1B[0m`);
      note2?.(line);
      if (pkg?.dependencies) delete pkg.dependencies[name];
      if (pkg?.devDependencies) delete pkg.devDependencies[name];
      const pinned = normalizeLabPackageJson(JSON.stringify(pkg));
      await pod.fs.writeFile("/package.json", pinned.body);
      try {
        pkg = JSON.parse(pinned.body);
      } catch {
      }
      const host = typeof getFiles === "function" ? getFiles() || {} : {};
      if (host && typeof host === "object" && Object.keys(host).length) {
        const relative = {};
        for (const [raw, body] of Object.entries(host)) {
          if (body == null) continue;
          relative[normalizeVfsPath(raw)] = typeof body === "string" ? body : String(body);
        }
        if (relative["package.json"]) {
          relative["package.json"] = pinned.body;
        }
        const healed = shimUninstallableBareImports(relative, { extraPackages: skipped });
        await syncLabVfsToGuest(healed.files, { force: true });
      }
    }
  }
  throw new Error("Too many unpublished packages in package.json");
}
async function pinGuestViteIfUnsupported(pod, installer, flags, terminal, signal) {
  const major = await viteMajorVersion(pod);
  if (major < 8) return;
  if (typeof installer?.install !== "function") return;
  termLine(terminal, `\x1B[90m${labConsoleName()} \xB7 pinning vite@7 (Vite 8 / rolldown is unsupported in Lab)\x1B[0m`);
  if (signal?.aborted) return;
  await installer.install("vite", TOOLCHAIN_DEV_DEPENDENCIES.vite, flags);
  const plugin = installSpecForPackage("@tailwindcss/vite");
  if (!plugin.skip) {
    await installer.install("@tailwindcss/vite", plugin.version, flags);
  }
}
async function prepareLabGuestForBuild(opts = {}) {
  const ac = new AbortController();
  if (opts.signal?.aborted) {
    throw new DOMException("Aborted", "AbortError");
  }
  opts.signal?.addEventListener("abort", () => ac.abort(), { once: true });
  const note2 = (line) => {
    if (typeof opts.onLog === "function") opts.onLog(line);
  };
  const onPhase = (phase, detail) => {
    publishLabPhase(phase, detail);
    if (typeof opts.onPhase === "function") opts.onPhase(phase, detail);
    if (phase === "install-log" && detail?.line) return;
    const label = {
      boot: "Starting runtime\u2026",
      sync: "Syncing project files\u2026",
      install: "Installing packages\u2026",
      "install-cached": "Using cached packages.",
      "install-done": "Packages ready."
    }[phase];
    if (label) note2(label);
  };
  const getFiles = typeof opts.getFiles === "function" ? opts.getFiles : opts.files ? () => opts.files : null;
  const warmPod = getLabRuntime();
  const light = Boolean(opts.light) && warmPod;
  const seed = getFiles ? getFiles() || {} : opts.files || null;
  if (warmPod) {
    if (light) {
      note2("Using cached runtime and project files.");
      onPhase("install-cached");
    } else {
      onPhase("sync");
      if (seed && Object.keys(seed).length) {
        await syncLabVfsToGuest(seed);
      }
    }
  } else {
    onPhase("boot");
    if (seed && Object.keys(seed).length) {
      await ensureLabSession({ files: seed });
      await syncLabVfsToGuest(seed);
    } else {
      await ensureLabSession();
    }
  }
  if (ac.signal.aborted) {
    throw new DOMException("Aborted", "AbortError");
  }
  const pod = getLabRuntime();
  if (!pod) {
    throw new Error("Lab runtime is not ready.");
  }
  onPhase("sync");
  let pkg = await waitForPackageJson(pod, {
    getFiles: light ? void 0 : getFiles || void 0,
    signal: ac.signal
  });
  if (!pkg) {
    throw new Error("package.json was not found in this project.");
  }
  pkg = await pinGuestPackageJson(pod, pkg) || pkg;
  const installFlags = {
    withDevDeps: true,
    transformModules: false,
    onProgress: (message) => {
      if (ac.signal.aborted) return;
      const line = String(message || "").trim();
      if (line) {
        onPhase("install-log", { line });
        note2(line);
      }
    }
  };
  const installed = await hasInstalledModules(pod);
  if (!installed) {
    onPhase("install");
    const installer = pod.packages;
    if (!installer?.installFromManifest && !installer?.install) {
      throw new Error("Package installer is unavailable in this browser session.");
    }
    if (installer.installFromManifest) {
      pkg = await installManifestSkippingUnpublished({
        pod,
        installer,
        pkg,
        flags: installFlags,
        getFiles,
        signal: ac.signal,
        note: (line) => {
          onPhase("install-log", { line });
          note2(line);
        }
      }) || pkg;
    } else {
      await installer.install(void 0, void 0, installFlags);
    }
    onPhase("install-done");
  } else {
    onPhase("install-cached");
  }
  const missingVitePkgs = light ? [] : await missingViteConfigPackages(pod);
  if (missingVitePkgs.length) {
    const installer = pod.packages;
    if (installer?.install) {
      onPhase("install", { reason: "vite-config-plugins", packages: missingVitePkgs });
      const pluginFlags = { ...installFlags, persist: false };
      for (const name of missingVitePkgs) {
        if (ac.signal.aborted) break;
        const spec = installSpecForPackage(name, pkg);
        if (spec.skip) continue;
        await installer.install(name, spec.version, pluginFlags);
      }
      onPhase("install-done", { reason: "vite-config-plugins" });
    }
  }
  if (ac.signal.aborted) {
    throw new DOMException("Aborted", "AbortError");
  }
  if (!light) {
    try {
      await pinGuestViteIfUnsupported(pod, pod.packages, installFlags, null, ac.signal);
    } catch {
    }
  }
  if (!warmPod && !light) {
    try {
      await seedGuestNativeWasm(pod);
    } catch {
    }
  }
  return { pod, pkg };
}
function pauseLabDevServerForPublish() {
  try {
    runningDevProc?.kill?.();
  } catch {
  }
  runningDevProc = null;
}

// resources/js/lab/lib/publishEsbuild.js
var LAB_VITE_PLUGINS = /* @__PURE__ */ new Set([
  "vite",
  "@vitejs/plugin-react",
  "@tailwindcss/vite"
]);
var ENTRY_CANDIDATES = ["/src/main.jsx", "/src/main.tsx", "/src/main.js", "/src/main.ts"];
var CSS_CANDIDATES = ["/src/index.css", "/src/styles.css", "/src/app.css"];
async function fileExists(pod, path) {
  if (typeof pod.fs.exists === "function") {
    return pod.fs.exists(path);
  }
  try {
    await pod.fs.readFile(path);
    return true;
  } catch {
    return false;
  }
}
async function resolveEntryFile(pod) {
  for (const path of ENTRY_CANDIDATES) {
    if (await fileExists(pod, path)) return path;
  }
  return null;
}
async function resolveCssFile(pod, entry) {
  for (const path of CSS_CANDIDATES) {
    if (await fileExists(pod, path)) return path;
  }
  if (!entry) return null;
  try {
    const raw = await pod.fs.readFile(entry, "utf8");
    const text = typeof raw === "string" ? raw : String(raw);
    const match = text.match(/import\s+['"](\.\/[^'"]+\.css)['"]/);
    if (match?.[1]) {
      const dir = entry.slice(0, entry.lastIndexOf("/")) || "/src";
      const resolved = `${dir}/${match[1].replace(/^\.\//, "")}`.replace(/\/+/g, "/");
      if (await fileExists(pod, resolved)) return resolved;
    }
  } catch {
  }
  return null;
}
async function hasExoticVitePlugins(pod) {
  const files = ["/vite.config.js", "/vite.config.ts", "/vite.config.mjs", "/vite.config.cjs"];
  for (const file of files) {
    try {
      const raw = await pod.fs.readFile(file, "utf8");
      const text = typeof raw === "string" ? raw : String(raw);
      const imports = [...text.matchAll(/from\s*['"]([^'"]+)['"]/g)].map((m) => m[1]);
      for (const spec of imports) {
        const name = spec.startsWith("@") ? spec.split("/").slice(0, 2).join("/") : spec.split("/")[0];
        if (!name || name.startsWith(".") || LAB_VITE_PLUGINS.has(name)) continue;
        if (name === "react" || name === "react-dom") continue;
        return true;
      }
    } catch {
    }
  }
  return false;
}
async function canUseEsbuildPublish(pod) {
  const entry = await resolveEntryFile(pod);
  if (!entry || !await fileExists(pod, "/index.html")) return false;
  if (await hasExoticVitePlugins(pod)) return false;
  const css = await resolveCssFile(pod, entry);
  if (css && !await fileExists(pod, "/node_modules/@tailwindcss/node/package.json")) {
    return false;
  }
  return true;
}
async function writeEsbuildPublishScript(pod, plan) {
  const cssLine = plan.css ? `'${plan.css}'` : "null";
  const script = [
    "import esbuild from 'esbuild'",
    "import { readFileSync, writeFileSync, mkdirSync, existsSync, readdirSync, statSync, copyFileSync, unlinkSync } from 'fs'",
    "import { dirname, join } from 'path'",
    "console.log('Production build: compiler loaded.')",
    "",
    `const ENTRY = '${plan.entry}'`,
    `const CSS_INPUT = ${cssLine}`,
    "const OUT_DIR = '/dist'",
    "const ASSETS = '/dist/assets'",
    "",
    "function ensureDir(path) {",
    "  if (!existsSync(path)) mkdirSync(path, { recursive: true })",
    "}",
    "",
    "function walkFiles(dir, out = []) {",
    "  if (!existsSync(dir)) return out",
    "  for (const name of readdirSync(dir)) {",
    "    if (!name || name === '.' || name === '..' || name === 'node_modules') continue",
    '    const full = join(dir, name).replace(/\\\\/g, "/")',
    "    let isDir = false",
    "    try { isDir = statSync(full).isDirectory() } catch { continue }",
    "    if (isDir) walkFiles(full, out)",
    "    else out.push(full)",
    "  }",
    "  return out",
    "}",
    "",
    "function extractCandidates(text) {",
    "  const found = new Set()",
    "  const patterns = [",
    '    /className\\s*=\\s*"([^"]+)"/g,',
    "    /className\\s*=\\s*'([^']+)'/g,",
    "    /className\\s*=\\s*\\{`([^`]+)`\\}/g,",
    '    /class\\s*=\\s*"([^"]+)"/g,',
    "    /class\\s*=\\s*'([^']+)'/g,",
    "  ]",
    "  for (const re of patterns) {",
    "    let match",
    "    while ((match = re.exec(text))) {",
    '      for (const token of String(match[1] || "").split(/\\s+/)) {',
    "        const value = token.trim()",
    "        if (value) found.add(value)",
    "      }",
    "    }",
    "  }",
    "  return [...found]",
    "}",
    "",
    "function scanCandidates() {",
    "  const set = new Set()",
    '  for (const file of walkFiles("/src")) {',
    "    if (!/\\.(jsx?|tsx?|html)$/i.test(file)) continue",
    "    try {",
    '      for (const c of extractCandidates(readFileSync(file, "utf8"))) set.add(c)',
    "    } catch { /* ignore */ }",
    "  }",
    "  try {",
    '    for (const c of extractCandidates(readFileSync("/index.html", "utf8"))) set.add(c)',
    "  } catch { /* ignore */ }",
    "  return [...set]",
    "}",
    "",
    "function copyPublicDir() {",
    "  if (!existsSync('/public')) return",
    "  for (const file of walkFiles('/public')) {",
    "    const rel = file.slice('/public/'.length)",
    "    const dest = `${OUT_DIR}/${rel}`",
    "    ensureDir(dirname(dest))",
    "    copyFileSync(file, dest)",
    "  }",
    "}",
    "",
    "function writeIndexHtml() {",
    "  let html = readFileSync('/index.html', 'utf8')",
    `  html = html.replace(/<script[^>]+type=["']module["'][^>]*>\\s*<\\/script>/i, '')`,
    `  html = html.replace(/<script[^>]+src=["']\\/src\\/[^"']+["'][^>]*>\\s*<\\/script>/i, '')`,
    "  const tags = []",
    `  if (CSS_INPUT) tags.push('<link rel="stylesheet" href="/assets/index.css">')`,
    `  tags.push('<script type="module" src="/assets/index.js"><\/script>')`,
    "  if (html.includes('</head>')) {",
    "    html = html.replace('</head>', `  ${tags.join('\\n  ')}\\n</head>`)",
    "  } else {",
    '    html += `\\n${tags.join("\\n")}\\n`',
    "  }",
    "  writeFileSync(`${OUT_DIR}/index.html`, html)",
    "}",
    "",
    // Keep the synthetic entry beside the real entry (/src/...) so `./App.jsx`
    // still resolves. Writing it at `/.__krikkit_publish_entry__.jsx` left
    // `import "./App.jsx"` in the shipped bundle and 404'd on the live host.
    "const JS_ENTRY = `${dirname(ENTRY)}/.__krikkit_publish_entry__.jsx`",
    'const entrySource = readFileSync(ENTRY, "utf8")',
    `const strippedEntry = entrySource.replace(/^\\s*import\\s+['"][^'"]+\\.css['"]\\s*;?\\s*$/gm, '')`,
    "writeFileSync(JS_ENTRY, strippedEntry)",
    "",
    "ensureDir(ASSETS)",
    "",
    "console.log('Production build: bundling React modules\u2026')",
    "await esbuild.build({",
    "  entryPoints: [JS_ENTRY],",
    "  bundle: true,",
    '  format: "esm",',
    '  platform: "browser",',
    '  target: ["es2020"],',
    '  jsx: "automatic",',
    "  minify: true,",
    "  sourcemap: false,",
    "  metafile: false,",
    '  logLevel: "warning",',
    "  outfile: `${ASSETS}/index.js`,",
    '  absWorkingDir: "/",',
    "  write: true,",
    "  plugins: [{",
    "    name: 'ignore-css-imports',",
    "    setup(build) {",
    "      build.onResolve({ filter: /\\.css$/ }, (args) => ({",
    "        path: args.path,",
    "        namespace: 'css-stub',",
    "      }))",
    "      build.onLoad({ filter: /.*/, namespace: 'css-stub' }, () => ({",
    "        contents: '',",
    "        loader: 'js',",
    "      }))",
    "    },",
    "  }],",
    "  define: {",
    `    "process.env.NODE_ENV": '"production"',`,
    "  },",
    "})",
    "console.log('Production build: React bundle written.')",
    "",
    "try {",
    "  if (existsSync(JS_ENTRY)) unlinkSync(JS_ENTRY)",
    "} catch { /* ignore */ }",
    "",
    "if (CSS_INPUT) {",
    "  console.log('Production build: loading stylesheet compiler\u2026')",
    "  const { compile, optimize } = await import('@tailwindcss/node')",
    '  const cssInput = readFileSync(CSS_INPUT, "utf8")',
    "  const compiled = await compile(cssInput, {",
    "    base: dirname(CSS_INPUT),",
    "    onDependency() {},",
    "  })",
    "  const css = compiled.build(scanCandidates())",
    "  const optimized = optimize(css, { minify: true })",
    "  writeFileSync(`${ASSETS}/index.css`, optimized.code)",
    "  console.log('Production build: stylesheet written.')",
    "}",
    "",
    "copyPublicDir()",
    "writeIndexHtml()",
    "console.log('Production build: index.html written.')",
    ""
  ].join("\n");
  await pod.fs.writeFile("/.__krikkit_esbuild_publish__.mjs", script);
}
async function resolveEsbuildPublishPlan(pod) {
  if (!await canUseEsbuildPublish(pod)) return null;
  const entry = await resolveEntryFile(pod);
  if (!entry) return null;
  const css = await resolveCssFile(pod, entry);
  return {
    mode: "esbuild",
    label: "esbuild production build",
    entry,
    css
  };
}

// resources/js/lab/lib/publishMemory.js
var PUBLISH_MEMORY_EVENT = "krikkit-publish-memory";
function suspendLabMemoryForPublish() {
  if (typeof window === "undefined") return;
  try {
    window.dispatchEvent(new CustomEvent(PUBLISH_MEMORY_EVENT, {
      detail: { suspended: true, at: Date.now() }
    }));
  } catch {
  }
}
function resumeLabMemoryAfterPublish() {
  if (typeof window === "undefined") return;
  try {
    window.dispatchEvent(new CustomEvent(PUBLISH_MEMORY_EVENT, {
      detail: { suspended: false, at: Date.now() }
    }));
  } catch {
  }
}
function sleepMs(ms) {
  return new Promise((resolve) => {
    window.setTimeout(resolve, Math.max(0, Number(ms) || 0));
  });
}

// resources/js/lab/lib/publishBuild.js
var DIST_FOLDERS = ["dist", "build", "out"];
var SKIP_INDEX_SEARCH = /(?:^|\/)(node_modules|\.git|\.next|\.vite|\.npm|\.cache)(?:\/|$)/i;
var SKIP_ZIP_PATH = /(?:^|\/)(node_modules|\.git|\.next|\.vite|\.npm|\.cache|src|public)(?:\/|$)/i;
var SOURCE_ROOT_MARKERS = ["package.json", "vite.config.js", "vite.config.ts", "vite.config.mjs", "vite.config.cjs"];
var MAX_ZIP_BYTES = 50 * 1024 * 1024;
var MAX_ZIP_FILES = 5e3;
function sleep2(ms, signal) {
  return new Promise((resolve, reject) => {
    if (signal?.aborted) {
      reject(new DOMException("Aborted", "AbortError"));
      return;
    }
    const id = setTimeout(resolve, ms);
    signal?.addEventListener("abort", () => {
      clearTimeout(id);
      reject(new DOMException("Aborted", "AbortError"));
    }, { once: true });
  });
}
async function fileExists2(pod, path) {
  if (typeof pod.fs.exists === "function") {
    return pod.fs.exists(path);
  }
  try {
    await pod.fs.readFile(path);
    return true;
  } catch {
    return false;
  }
}
async function viteMajorVersion2(pod) {
  try {
    const raw = await pod.fs.readFile("/node_modules/vite/package.json", "utf8");
    const version = JSON.parse(typeof raw === "string" ? raw : String(raw))?.version;
    const major = parseInt(String(version || "").split(".")[0], 10);
    return Number.isFinite(major) ? major : 0;
  } catch {
    return 0;
  }
}
async function resolveViteConfigFile(pod) {
  for (const file of ["/vite.config.js", "/vite.config.ts", "/vite.config.mjs", "/vite.config.cjs"]) {
    if (await fileExists2(pod, file)) return file;
  }
  return "/vite.config.js";
}
async function isPublishOutputRoot(pod, root) {
  const normalized = root.replace(/\/+$/, "") || "/";
  if (!normalized || normalized === "/" || normalized === "/src" || normalized.startsWith("/src/")) {
    return false;
  }
  if (!await fileExists2(pod, `${normalized}/index.html`)) {
    return false;
  }
  for (const marker of SOURCE_ROOT_MARKERS) {
    if (await fileExists2(pod, `/${marker}`) && normalized === "/") {
      return false;
    }
  }
  return true;
}
async function resolveBuildPlan(pod, pkg) {
  const esbuildPlan = await resolveEsbuildPublishPlan(pod);
  if (esbuildPlan) return esbuildPlan;
  const scripts = pkg?.scripts && typeof pkg.scripts === "object" ? pkg.scripts : {};
  const buildScript = typeof scripts.build === "string" ? scripts.build.trim() : "";
  const viteMajor = await viteMajorVersion2(pod);
  const viteLoaderArgs = viteMajor >= 6 ? ["--configLoader", "native"] : [];
  if (await fileExists2(pod, "/node_modules/vite/package.json")) {
    return {
      mode: "programmatic",
      label: "vite build",
      viteMajor,
      configFile: await resolveViteConfigFile(pod)
    };
  }
  if (buildScript) {
    if (/\bvite\b/i.test(buildScript) && viteLoaderArgs.length) {
      return { mode: "spawn", cmd: "npm", args: ["run", "build", "--", ...viteLoaderArgs], label: "npm run build" };
    }
    return { mode: "spawn", cmd: "npm", args: ["run", "build"], label: "npm run build" };
  }
  return null;
}
async function writeProgrammaticViteBuildScript(pod, plan) {
  const loaderLine = plan.viteMajor >= 6 ? "  configLoader: 'native',\n" : "";
  const script = [
    "import { build } from 'vite'",
    "await build({",
    `  configFile: '${plan.configFile}',`,
    loaderLine,
    "  logLevel: 'warn',",
    "  build: {",
    "    sourcemap: false,",
    "    reportCompressedSize: false,",
    "    cssCodeSplit: true,",
    "  },",
    "})",
    ""
  ].join("\n");
  await pod.fs.writeFile("/.__krikkit_publish_build__.mjs", script);
}
async function clearGuestBuildCaches(pod) {
  for (const path of ["/.vite", "/node_modules/.vite", "/.next/cache"]) {
    try {
      await pod.fs.rm(path, { recursive: true, force: true });
    } catch {
    }
  }
}
async function readViteOutDir(pod) {
  const files = ["/vite.config.js", "/vite.config.ts", "/vite.config.mjs", "/vite.config.cjs"];
  for (const file of files) {
    try {
      const raw = await pod.fs.readFile(file, "utf8");
      const text = typeof raw === "string" ? raw : String(raw);
      const match = text.match(/outDir\s*:\s*['"`]([^'"`]+)['"`]/);
      if (match?.[1]) {
        const value = match[1].trim();
        return value.startsWith("/") ? value.replace(/\/+/g, "/") : `/${value}`.replace(/\/+/g, "/");
      }
    } catch {
    }
  }
  return null;
}
async function findIndexHtmlRoot(pod, dir, depth, maxDepth) {
  if (depth > maxDepth || SKIP_INDEX_SEARCH.test(dir)) return null;
  const normalized = dir.replace(/\/+$/, "") || "/";
  const indexPath = normalized === "/" ? "/index.html" : `${normalized}/index.html`;
  if (await fileExists2(pod, indexPath)) {
    return normalized;
  }
  let names = [];
  try {
    names = await pod.fs.readdir(normalized);
  } catch {
    return null;
  }
  if (!Array.isArray(names)) return null;
  for (const name of names) {
    if (!name || name === "." || name === "..") continue;
    const full = normalized === "/" ? `/${name}` : `${normalized}/${name}`;
    if (SKIP_INDEX_SEARCH.test(full)) continue;
    let isDir = false;
    try {
      const st = await pod.fs.stat(full);
      isDir = Boolean(st?.isDirectory);
    } catch {
      try {
        const children = await pod.fs.readdir(full);
        isDir = Array.isArray(children);
      } catch {
        isDir = false;
      }
    }
    if (!isDir) continue;
    const found = await findIndexHtmlRoot(pod, full, depth + 1, maxDepth);
    if (found) return found;
  }
  return null;
}
async function findDistRootOnce(pod) {
  const customOut = await readViteOutDir(pod);
  const candidates = [.../* @__PURE__ */ new Set([
    ...customOut ? [customOut] : [],
    ...DIST_FOLDERS.map((folder) => `/${folder}`)
  ])];
  for (const root of candidates) {
    if (await isPublishOutputRoot(pod, root)) {
      return root;
    }
    const nested = await findIndexHtmlRoot(pod, root, 0, 3);
    if (nested && await isPublishOutputRoot(pod, nested)) {
      return nested;
    }
  }
  return null;
}
async function waitForDistRoot(pod, opts = {}) {
  const timeoutMs = opts.timeoutMs ?? 3e4;
  const started = Date.now();
  let lastListing = "";
  while (Date.now() - started < timeoutMs) {
    if (opts.signal?.aborted) {
      throw new DOMException("Aborted", "AbortError");
    }
    const root = await findDistRootOnce(pod);
    if (root) {
      opts.onLog?.(`Found production output in ${root.replace(/^\//, "")}/`);
      return root;
    }
    try {
      const names = await pod.fs.readdir("/");
      lastListing = Array.isArray(names) ? names.join(", ") : "";
    } catch {
      lastListing = "";
    }
    await sleep2(Math.min(300, 80 + Math.floor((Date.now() - started) / 40)), opts.signal);
  }
  throw new Error(
    lastListing ? `Build finished but no dist/ folder with index.html was found. Root entries: ${lastListing}. Check the build log for errors.` : "Build finished but no dist/ folder with index.html was found. Check the build log for errors."
  );
}
async function walkGuestDirectory(pod, dirPath, prefix, out) {
  if (SKIP_ZIP_PATH.test(dirPath)) return;
  const names = await pod.fs.readdir(dirPath);
  if (!Array.isArray(names)) return;
  for (const name of names) {
    if (!name || name === "." || name === "..") continue;
    const full = `${dirPath}/${name}`.replace(/\/+/g, "/");
    if (SKIP_ZIP_PATH.test(full)) continue;
    const rel = prefix ? `${prefix}/${name}` : name;
    let isDir = false;
    try {
      const st = await pod.fs.stat(full);
      isDir = Boolean(st?.isDirectory);
    } catch {
      try {
        const children = await pod.fs.readdir(full);
        isDir = Array.isArray(children);
      } catch {
        isDir = false;
      }
    }
    if (isDir) {
      await walkGuestDirectory(pod, full, rel, out);
      continue;
    }
    if (Object.keys(out).length >= MAX_ZIP_FILES) {
      throw new Error(`The production build contains more than ${MAX_ZIP_FILES} files. Remove unused assets and try again.`);
    }
    const data = await pod.fs.readFile(full);
    out[rel.replace(/\\/g, "/")] = data instanceof Uint8Array ? data : new TextEncoder().encode(String(data));
  }
}
async function zipGuestDirectory(pod, rootPath) {
  const files = {};
  await walkGuestDirectory(pod, rootPath, "", files);
  if (!Object.keys(files).length) {
    throw new Error("The production build folder is empty.");
  }
  const zipped = zipSync(files, { level: 1 });
  return new Blob([zipped], { type: "application/zip" });
}
async function clearPreviousDist(pod) {
  for (const folder of DIST_FOLDERS) {
    try {
      await pod.fs.rm(`/${folder}`, { recursive: true, force: true });
    } catch {
    }
  }
}
async function buildLabPublishArtifact(opts = {}) {
  const onPhase = typeof opts.onPhase === "function" ? opts.onPhase : () => {
  };
  const note2 = (line) => {
    if (typeof opts.onLog === "function") opts.onLog(line);
  };
  suspendLabMemoryForPublish();
  note2("Publish build started.");
  pauseLabDevServerForPublish();
  note2("Preview paused to free memory.");
  await sleepMs(350);
  try {
    const { pod, pkg } = await prepareLabGuestForBuild({
      getFiles: opts.getFiles,
      onPhase,
      onLog: opts.onLog,
      signal: opts.signal,
      light: true
    });
    if (!getLabRuntime()) {
      throw new Error("Lab runtime is not ready.");
    }
    await clearPreviousDist(pod);
    await clearGuestBuildCaches(pod);
    const buildPlan = await resolveBuildPlan(pod, pkg);
    if (!buildPlan) {
      throw new Error('This project has no build script. Add "build": "vite build" to package.json.');
    }
    onPhase("build", { label: buildPlan.label });
    note2(`Running ${buildPlan.label}\u2026`);
    const logLines = [];
    const pushLog = (chunk) => {
      const text = String(chunk || "").replace(/\r/g, "");
      if (!text.trim()) return;
      for (const line of text.split("\n")) {
        const trimmed = line.trim();
        if (!trimmed) continue;
        logLines.push(trimmed);
        if (logLines.length > 120) logLines.shift();
        opts.onLog?.(trimmed);
      }
    };
    const buildStarted = Date.now();
    let proc;
    if (buildPlan.mode === "esbuild") {
      await writeEsbuildPublishScript(pod, buildPlan);
      proc = await pod.spawn("node", [".__krikkit_esbuild_publish__.mjs"], {
        cwd: "/",
        signal: opts.signal,
        env: {
          CI: "true",
          NODE_ENV: "production",
          npm_config_update_notifier: "false"
        }
      });
    } else if (buildPlan.mode === "programmatic") {
      await writeProgrammaticViteBuildScript(pod, buildPlan);
      proc = await pod.spawn("node", [".__krikkit_publish_build__.mjs"], {
        cwd: "/",
        signal: opts.signal,
        env: {
          CI: "true",
          NODE_ENV: "production",
          npm_config_update_notifier: "false"
        }
      });
    } else {
      proc = await pod.spawn(buildPlan.cmd, buildPlan.args, {
        cwd: "/",
        signal: opts.signal,
        env: {
          CI: "true",
          NODE_ENV: "production",
          npm_config_update_notifier: "false"
        }
      });
    }
    proc.on?.("output", pushLog);
    proc.on?.("error", pushLog);
    const result = await proc.completion;
    const buildMs = Date.now() - buildStarted;
    note2(`Build exited with code ${result?.exitCode ?? 1} (${buildMs}ms).`);
    if ((result?.exitCode ?? 1) !== 0) {
      const tail = String(result?.stderr || result?.stdout || "").trim();
      const message = tail ? `Production build failed.
${tail.slice(-1200)}` : "Production build failed.";
      throw new Error(message);
    }
    if (buildMs < 200 && logLines.length === 0) {
      const tail = String(result?.stderr || result?.stdout || "").trim();
      if (tail) note2(tail);
    }
    try {
      await pod.fs.rm("/.__krikkit_publish_build__.mjs", { force: true });
    } catch {
    }
    try {
      await pod.fs.rm("/.__krikkit_esbuild_publish__.mjs", { force: true });
    } catch {
    }
    try {
      await pod.fs.rm("/.__krikkit_publish_entry__.jsx", { force: true });
    } catch {
    }
    try {
      await pod.fs.rm("/src/.__krikkit_publish_entry__.jsx", { force: true });
    } catch {
    }
    onPhase("pack");
    note2("Waiting for build output\u2026");
    const distRoot = await waitForDistRoot(pod, { signal: opts.signal, onLog: note2 });
    if (!await isPublishOutputRoot(pod, distRoot)) {
      throw new Error("The production build folder looks invalid. Expected dist/index.html, not project source files.");
    }
    const blob = await zipGuestDirectory(pod, distRoot);
    if (blob.size > MAX_ZIP_BYTES) {
      throw new Error(`The production build is too large to upload (${formatBytes(blob.size)}). Remove unused assets or raise LAB_PUBLISH_ARTIFACT_MAX_BYTES on the server.`);
    }
    note2(`Packaged ${formatBytes(blob.size)} from ${distRoot.replace(/^\//, "")}/`);
    onPhase("pack-done", { bytes: blob.size });
    return blob;
  } finally {
    resumeLabMemoryAfterPublish();
  }
}
function formatBytes(bytes) {
  if (bytes < 1024) return `${bytes} B`;
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
  return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

// tests/fixtures/runtime/guest-browser-harness.js
var report = document.getElementById("report");
var lines = [];
var pods = [];
var checks = [];
var note = (value) => {
  lines.push(value);
  report.textContent = lines.join("\n");
};
var check = (condition, label) => {
  checks.push({ label, pass: Boolean(condition) });
  note(`${condition ? "PASS" : "FAIL"} ${label}`);
};
document.cookie = "krikkit_boundary_sentinel=fictional-test; Path=/; SameSite=Lax";
async function run() {
  note("Starting actual isolated browser runtime\u2026");
  const serverSignals = [];
  for (let index = 0; index < 2; index++) {
    let ready;
    const listening = new Promise((resolve) => {
      ready = resolve;
    });
    serverSignals.push(listening);
    const pod = await bootGuestRuntime({ files: {}, workdir: "/", env: { HOME: "/", PWD: "/" }, onServerReady: (port, url) => ready({ port, url }) });
    pods.push(pod);
    check(pod.origin !== location.origin, `runtime ${index + 1} uses distinct origin`);
    await pod.fs.writeFile("/proof.txt", `fictional-project-${index}`);
    check(await pod.fs.readFile("/proof.txt", "utf8") === `fictional-project-${index}`, `runtime ${index + 1} filesystem roundtrip`);
    const finite = await pod.spawn("node", ["-e", 'console.log("actual-guest-process")']);
    const result = await finite.completion;
    check(result.exitCode === 0 && result.stdout.includes("actual-guest-process"), `runtime ${index + 1} actual process completion`);
  }
  for (let index = 0; index < pods.length; index++) {
    const pod = pods[index];
    const html = `<!doctype html><title>Guest boundary proof ${index + 1}</title><h1>Guest ${index + 1}</h1><pre id="boundary">Checking\u2026</pre><script>
            (async()=>{
              const output={};
              try { void parent.document.body; output.parentBlocked=false; } catch { output.parentBlocked=true; }
              output.platformCookieAbsent=!document.cookie.includes('krikkit_boundary_sentinel');
              try { const response=await fetch(${JSON.stringify(`${location.origin}/lab/builder/drafts`)},{credentials:'include'}); output.ownerApiBlocked=response.status===401||response.status===403||response.status===419; } catch { output.ownerApiBlocked=true; }
              try { void parent.frames[${index ? 0 : 1}].document.body; output.siblingBlocked=false; } catch { output.siblingBlocked=true; }
              document.getElementById('boundary').textContent=JSON.stringify(output);
              parent.postMessage({type:'boundary-proof',index:${index},output},${JSON.stringify(location.origin)});
            })();
          <\/script>`;
    await pod.fs.writeFile("/server.mjs", `import http from 'node:http'; http.createServer((req,res)=>{res.writeHead(200,{'Content-Type':'text/html'});res.end(${JSON.stringify(html)})}).listen(5173,'0.0.0.0');`);
    await pod.spawn("node", ["/server.mjs"]);
    const server = await Promise.race([serverSignals[index], new Promise((_, reject) => setTimeout(() => reject(new Error("Guest server did not listen.")), 3e4))]);
    check(server.port === 5173 && server.url.startsWith(pod.origin + "/"), `runtime ${index + 1} preview origin`);
    const frame = document.createElement("iframe");
    frame.title = `Guest boundary proof ${index + 1}`;
    frame.sandbox = "allow-scripts allow-same-origin";
    frame.allow = "cross-origin-isolated";
    frame.src = server.url + "/";
    document.getElementById("previews").appendChild(frame);
    const proof = await new Promise((resolve, reject) => {
      const timer = setTimeout(() => {
        window.removeEventListener("message", receive);
        reject(new Error("Guest proof timed out."));
      }, 3e4);
      function receive(event) {
        if (event.source !== frame.contentWindow || event.origin !== pod.origin || event.data?.type !== "boundary-proof" || event.data.index !== index) return;
        clearTimeout(timer);
        window.removeEventListener("message", receive);
        resolve(event.data.output);
      }
      window.addEventListener("message", receive);
    });
    for (const [name, value] of Object.entries(proof)) check(value === true, `guest ${index + 1} ${name}`);
  }
  check(pods[0].origin !== pods[1].origin, "sibling runtime origins differ");
  check(await pods[0].fs.readFile("/proof.txt", "utf8") === "fictional-project-0", "sibling filesystem remains independent");
  note(`RESULT ${checks.filter((item) => item.pass).length}/${checks.length}`);
  document.body.dataset.finished = "true";
  const button = document.createElement("button");
  button.textContent = "Run actual production build";
  button.addEventListener("click", async () => {
    button.disabled = true;
    try {
      for (const pod of pods) await pod.teardown();
      const files = {
        "package.json": JSON.stringify({ name: "fictional-runtime-build", private: true, type: "module", scripts: { build: "vite build" }, dependencies: { react: "19.1.1", "react-dom": "19.1.1" }, devDependencies: { vite: "^7.0.0" } }),
        "index.html": '<!doctype html><html><head><title>Fictional runtime build</title></head><body><div id="root"></div><script type="module" src="/src/main.jsx"><\/script></body></html>',
        "src/main.jsx": 'import React from "react";import{createRoot}from"react-dom/client";createRoot(document.getElementById("root")).render(<main><h1>Built in isolated guest runtime</h1></main>);',
        "vite.config.js": 'export default {build:{target:"es2022"}};'
      };
      const artifact = await buildLabPublishArtifact({ getFiles: () => files, onLog: (value) => note(`BUILD ${value}`) });
      check(artifact instanceof Blob && artifact.size > 100, "actual isolated production build awaited and packaged");
      const link = document.createElement("a");
      link.href = URL.createObjectURL(artifact);
      link.download = "fictional-isolated-site.zip";
      link.textContent = "Download actual built artifact";
      document.body.appendChild(link);
      note(`BUILD RESULT ${artifact.size} bytes`);
    } catch (error) {
      note(`BUILD ERROR ${error.message}`);
    } finally {
      await destroyLabSession();
    }
  });
  document.body.appendChild(button);
}
run().catch((error) => {
  note(`ERROR ${error.message}`);
  document.body.dataset.finished = "error";
});
window.addEventListener("pagehide", () => {
  for (const pod of pods) void pod.teardown();
}, { once: true });
