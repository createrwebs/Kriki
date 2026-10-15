import { n as Ga } from "./rolldown-runtime-DpiKQypI.js";
var qn = /* @__PURE__ */ Ga({
  Deflate: () => $a,
  Inflate: () => Ba,
  constants: () => Xa,
  default: () => Jn,
  deflate: () => Fa,
  deflateRaw: () => Ma,
  gzip: () => Ha,
  inflate: () => Pa,
  inflateRaw: () => Ka,
  ungzip: () => Ya
}), ja = 4, pt = 0, kt = 1, Wa = 2;
function oe(e) {
  let a = e.length;
  for (; --a >= 0; ) e[a] = 0;
}
var Va = 29, st = 256, ra = 286, ce = 30, la = 19, _a = 573, we = 15, Ke = 16, Ja = 7, ct = 256, Qa = 16, qa = 17, ei = 18, nt = new Uint8Array([
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
  0
]), Ce = new Uint8Array([
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
  13
]), ti = new Uint8Array([
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  0,
  2,
  3,
  7
]), oa = new Uint8Array([
  16,
  17,
  18,
  0,
  8,
  7,
  9,
  6,
  10,
  5,
  11,
  4,
  12,
  3,
  13,
  2,
  14,
  1,
  15
]), ai = 512, K = new Array(576);
oe(K);
var ve = new Array(60);
oe(ve);
var pe = new Array(ai);
oe(pe);
var ke = new Array(256);
oe(ke);
var wt = new Array(Va);
oe(wt);
var $e = new Array(ce);
oe($e);
function Ye(e, a, t, i, r) {
  this.static_tree = e, this.extra_bits = a, this.extra_base = t, this.elems = i, this.max_length = r, this.has_stree = e && e.length;
}
var ha, fa, da;
function Xe(e, a) {
  this.dyn_tree = e, this.max_code = 0, this.stat_desc = a;
}
var ua = (e) => e < 256 ? pe[e] : pe[256 + (e >>> 7)], Ee = (e, a) => {
  e.pending_buf[e.pending++] = a & 255, e.pending_buf[e.pending++] = a >>> 8 & 255;
}, N = (e, a, t) => {
  e.bi_valid > Ke - t ? (e.bi_buf |= a << e.bi_valid & 65535, Ee(e, e.bi_buf), e.bi_buf = a >> Ke - e.bi_valid, e.bi_valid += t - Ke) : (e.bi_buf |= a << e.bi_valid & 65535, e.bi_valid += t);
}, M = (e, a, t) => {
  N(e, t[a * 2], t[a * 2 + 1]);
}, sa = (e, a) => {
  let t = 0;
  do
    t |= e & 1, e >>>= 1, t <<= 1;
  while (--a > 0);
  return t >>> 1;
}, ii = (e) => {
  e.bi_valid === 16 ? (Ee(e, e.bi_buf), e.bi_buf = 0, e.bi_valid = 0) : e.bi_valid >= 8 && (e.pending_buf[e.pending++] = e.bi_buf & 255, e.bi_buf >>= 8, e.bi_valid -= 8);
}, ni = (e, a) => {
  const t = a.dyn_tree, i = a.max_code, r = a.stat_desc.static_tree, n = a.stat_desc.has_stree, o = a.stat_desc.extra_bits, _ = a.stat_desc.extra_base, s = a.stat_desc.max_length;
  let l, h, m, u, f, c, R = 0;
  for (u = 0; u <= we; u++) e.bl_count[u] = 0;
  for (t[e.heap[e.heap_max] * 2 + 1] = 0, l = e.heap_max + 1; l < _a; l++)
    h = e.heap[l], u = t[t[h * 2 + 1] * 2 + 1] + 1, u > s && (u = s, R++), t[h * 2 + 1] = u, !(h > i) && (e.bl_count[u]++, f = 0, h >= _ && (f = o[h - _]), c = t[h * 2], e.opt_len += c * (u + f), n && (e.static_len += c * (r[h * 2 + 1] + f)));
  if (R !== 0) {
    do {
      for (u = s - 1; e.bl_count[u] === 0; ) u--;
      e.bl_count[u]--, e.bl_count[u + 1] += 2, e.bl_count[s]--, R -= 2;
    } while (R > 0);
    for (u = s; u !== 0; u--)
      for (h = e.bl_count[u]; h !== 0; )
        m = e.heap[--l], !(m > i) && (t[m * 2 + 1] !== u && (e.opt_len += (u - t[m * 2 + 1]) * t[m * 2], t[m * 2 + 1] = u), h--);
  }
}, ca = (e, a, t) => {
  const i = new Array(16);
  let r = 0, n, o;
  for (n = 1; n <= we; n++)
    r = r + t[n - 1] << 1, i[n] = r;
  for (o = 0; o <= a; o++) {
    let _ = e[o * 2 + 1];
    _ !== 0 && (e[o * 2] = sa(i[_]++, _));
  }
}, ri = () => {
  let e, a, t, i, r;
  const n = new Array(16);
  for (t = 0, i = 0; i < 28; i++)
    for (wt[i] = t, e = 0; e < 1 << nt[i]; e++) ke[t++] = i;
  for (ke[t - 1] = i, r = 0, i = 0; i < 16; i++)
    for ($e[i] = r, e = 0; e < 1 << Ce[i]; e++) pe[r++] = i;
  for (r >>= 7; i < ce; i++)
    for ($e[i] = r << 7, e = 0; e < 1 << Ce[i] - 7; e++) pe[256 + r++] = i;
  for (a = 0; a <= we; a++) n[a] = 0;
  for (e = 0; e <= 143; )
    K[e * 2 + 1] = 8, e++, n[8]++;
  for (; e <= 255; )
    K[e * 2 + 1] = 9, e++, n[9]++;
  for (; e <= 279; )
    K[e * 2 + 1] = 7, e++, n[7]++;
  for (; e <= 287; )
    K[e * 2 + 1] = 8, e++, n[8]++;
  for (ca(K, 287, n), e = 0; e < ce; e++)
    ve[e * 2 + 1] = 5, ve[e * 2] = sa(e, 5);
  ha = new Ye(K, nt, 257, ra, we), fa = new Ye(ve, Ce, 0, ce, we), da = new Ye(new Array(0), ti, 0, la, Ja);
}, wa = (e) => {
  let a;
  for (a = 0; a < ra; a++) e.dyn_ltree[a * 2] = 0;
  for (a = 0; a < ce; a++) e.dyn_dtree[a * 2] = 0;
  for (a = 0; a < la; a++) e.bl_tree[a * 2] = 0;
  e.dyn_ltree[ct * 2] = 1, e.opt_len = e.static_len = 0, e.sym_next = e.matches = 0;
}, va = (e) => {
  e.bi_valid > 8 ? Ee(e, e.bi_buf) : e.bi_valid > 0 && (e.pending_buf[e.pending++] = e.bi_buf), e.bi_buf = 0, e.bi_valid = 0;
}, Et = (e, a, t, i) => {
  const r = a * 2, n = t * 2;
  return e[r] < e[n] || e[r] === e[n] && i[a] <= i[t];
}, Ge = (e, a, t) => {
  const i = e.heap[t];
  let r = t << 1;
  for (; r <= e.heap_len && (r < e.heap_len && Et(a, e.heap[r + 1], e.heap[r], e.depth) && r++, !Et(a, i, e.heap[r], e.depth)); )
    e.heap[t] = e.heap[r], t = r, r <<= 1;
  e.heap[t] = i;
}, mt = (e, a, t) => {
  let i, r, n = 0, o, _;
  if (e.sym_next !== 0) do
    i = e.pending_buf[e.sym_buf + n++] & 255, i += (e.pending_buf[e.sym_buf + n++] & 255) << 8, r = e.pending_buf[e.sym_buf + n++], i === 0 ? M(e, r, a) : (o = ke[r], M(e, o + st + 1, a), _ = nt[o], _ !== 0 && (r -= wt[o], N(e, r, _)), i--, o = ua(i), M(e, o, t), _ = Ce[o], _ !== 0 && (i -= $e[o], N(e, i, _)));
  while (n < e.sym_next);
  M(e, ct, a);
}, rt = (e, a) => {
  const t = a.dyn_tree, i = a.stat_desc.static_tree, r = a.stat_desc.has_stree, n = a.stat_desc.elems;
  let o, _, s = -1, l;
  for (e.heap_len = 0, e.heap_max = _a, o = 0; o < n; o++) t[o * 2] !== 0 ? (e.heap[++e.heap_len] = s = o, e.depth[o] = 0) : t[o * 2 + 1] = 0;
  for (; e.heap_len < 2; )
    l = e.heap[++e.heap_len] = s < 2 ? ++s : 0, t[l * 2] = 1, e.depth[l] = 0, e.opt_len--, r && (e.static_len -= i[l * 2 + 1]);
  for (a.max_code = s, o = e.heap_len >> 1; o >= 1; o--) Ge(e, t, o);
  l = n;
  do
    o = e.heap[1], e.heap[1] = e.heap[e.heap_len--], Ge(e, t, 1), _ = e.heap[1], e.heap[--e.heap_max] = o, e.heap[--e.heap_max] = _, t[l * 2] = t[o * 2] + t[_ * 2], e.depth[l] = (e.depth[o] >= e.depth[_] ? e.depth[o] : e.depth[_]) + 1, t[o * 2 + 1] = t[_ * 2 + 1] = l, e.heap[1] = l++, Ge(e, t, 1);
  while (e.heap_len >= 2);
  e.heap[--e.heap_max] = e.heap[1], ni(e, a), ca(t, s, e.bl_count);
}, yt = (e, a, t) => {
  let i, r = -1, n, o = a[1], _ = 0, s = 7, l = 4;
  for (o === 0 && (s = 138, l = 3), a[(t + 1) * 2 + 1] = 65535, i = 0; i <= t; i++)
    n = o, o = a[(i + 1) * 2 + 1], !(++_ < s && n === o) && (_ < l ? e.bl_tree[n * 2] += _ : n !== 0 ? (n !== r && e.bl_tree[n * 2]++, e.bl_tree[32]++) : _ <= 10 ? e.bl_tree[34]++ : e.bl_tree[36]++, _ = 0, r = n, o === 0 ? (s = 138, l = 3) : n === o ? (s = 6, l = 3) : (s = 7, l = 4));
}, xt = (e, a, t) => {
  let i, r = -1, n, o = a[1], _ = 0, s = 7, l = 4;
  for (o === 0 && (s = 138, l = 3), i = 0; i <= t; i++)
    if (n = o, o = a[(i + 1) * 2 + 1], !(++_ < s && n === o)) {
      if (_ < l) do
        M(e, n, e.bl_tree);
      while (--_ !== 0);
      else n !== 0 ? (n !== r && (M(e, n, e.bl_tree), _--), M(e, Qa, e.bl_tree), N(e, _ - 3, 2)) : _ <= 10 ? (M(e, qa, e.bl_tree), N(e, _ - 3, 3)) : (M(e, ei, e.bl_tree), N(e, _ - 11, 7));
      _ = 0, r = n, o === 0 ? (s = 138, l = 3) : n === o ? (s = 6, l = 3) : (s = 7, l = 4);
    }
}, li = (e) => {
  let a;
  for (yt(e, e.dyn_ltree, e.l_desc.max_code), yt(e, e.dyn_dtree, e.d_desc.max_code), rt(e, e.bl_desc), a = 18; a >= 3 && e.bl_tree[oa[a] * 2 + 1] === 0; a--) ;
  return e.opt_len += 3 * (a + 1) + 5 + 5 + 4, a;
}, _i = (e, a, t, i) => {
  let r;
  for (N(e, a - 257, 5), N(e, t - 1, 5), N(e, i - 4, 4), r = 0; r < i; r++) N(e, e.bl_tree[oa[r] * 2 + 1], 3);
  xt(e, e.dyn_ltree, a - 1), xt(e, e.dyn_dtree, t - 1);
}, oi = (e) => {
  let a = 4093624447, t;
  for (t = 0; t <= 31; t++, a >>>= 1) if (a & 1 && e.dyn_ltree[t * 2] !== 0) return pt;
  if (e.dyn_ltree[18] !== 0 || e.dyn_ltree[20] !== 0 || e.dyn_ltree[26] !== 0) return kt;
  for (t = 32; t < st; t++) if (e.dyn_ltree[t * 2] !== 0) return kt;
  return pt;
}, zt = !1, hi = (e) => {
  zt || (ri(), zt = !0), e.l_desc = new Xe(e.dyn_ltree, ha), e.d_desc = new Xe(e.dyn_dtree, fa), e.bl_desc = new Xe(e.bl_tree, da), e.bi_buf = 0, e.bi_valid = 0, wa(e);
}, ba = (e, a, t, i) => {
  N(e, 0 + (i ? 1 : 0), 3), va(e), Ee(e, t), Ee(e, ~t), t && e.pending_buf.set(e.window.subarray(a, a + t), e.pending), e.pending += t;
}, fi = (e) => {
  N(e, 2, 3), M(e, ct, K), ii(e);
}, di = (e, a, t, i) => {
  let r, n, o = 0;
  e.level > 0 ? (e.strm.data_type === Wa && (e.strm.data_type = oi(e)), rt(e, e.l_desc), rt(e, e.d_desc), o = li(e), r = e.opt_len + 3 + 7 >>> 3, n = e.static_len + 3 + 7 >>> 3, n <= r && (r = n)) : r = n = t + 5, t + 4 <= r && a !== -1 ? ba(e, a, t, i) : e.strategy === ja || n === r ? (N(e, 2 + (i ? 1 : 0), 3), mt(e, K, ve)) : (N(e, 4 + (i ? 1 : 0), 3), _i(e, e.l_desc.max_code + 1, e.d_desc.max_code + 1, o + 1), mt(e, e.dyn_ltree, e.dyn_dtree)), wa(e), i && va(e);
}, ui = (e, a, t) => (e.pending_buf[e.sym_buf + e.sym_next++] = a, e.pending_buf[e.sym_buf + e.sym_next++] = a >> 8, e.pending_buf[e.sym_buf + e.sym_next++] = t, a === 0 ? e.dyn_ltree[t * 2]++ : (e.matches++, a--, e.dyn_ltree[(ke[t] + st + 1) * 2]++, e.dyn_dtree[ua(a) * 2]++), e.sym_next === e.sym_end), si = {
  _tr_init: hi,
  _tr_stored_block: ba,
  _tr_flush_block: di,
  _tr_tally: ui,
  _tr_align: fi
}, ci = (e, a, t, i) => {
  let r = e & 65535 | 0, n = e >>> 16 & 65535 | 0, o = 0;
  for (; t !== 0; ) {
    o = t > 2e3 ? 2e3 : t, t -= o;
    do
      r = r + a[i++] | 0, n = n + r | 0;
    while (--o);
    r %= 65521, n %= 65521;
  }
  return r | n << 16 | 0;
}, me = ci, wi = () => {
  let e, a = [];
  for (var t = 0; t < 256; t++) {
    e = t;
    for (var i = 0; i < 8; i++) e = e & 1 ? 3988292384 ^ e >>> 1 : e >>> 1;
    a[t] = e;
  }
  return a;
}, vi = new Uint32Array(wi()), bi = (e, a, t, i) => {
  const r = vi, n = i + t;
  e ^= -1;
  for (let o = i; o < n; o++) e = e >>> 8 ^ r[(e ^ a[o]) & 255];
  return e ^ -1;
}, D = bi, ee = {
  2: "need dictionary",
  1: "stream end",
  0: "",
  "-1": "file error",
  "-2": "stream error",
  "-3": "data error",
  "-4": "insufficient memory",
  "-5": "buffer error",
  "-6": "incompatible version"
}, ie = {
  Z_NO_FLUSH: 0,
  Z_PARTIAL_FLUSH: 1,
  Z_SYNC_FLUSH: 2,
  Z_FULL_FLUSH: 3,
  Z_FINISH: 4,
  Z_BLOCK: 5,
  Z_TREES: 6,
  Z_OK: 0,
  Z_STREAM_END: 1,
  Z_NEED_DICT: 2,
  Z_ERRNO: -1,
  Z_STREAM_ERROR: -2,
  Z_DATA_ERROR: -3,
  Z_MEM_ERROR: -4,
  Z_BUF_ERROR: -5,
  Z_NO_COMPRESSION: 0,
  Z_BEST_SPEED: 1,
  Z_BEST_COMPRESSION: 9,
  Z_DEFAULT_COMPRESSION: -1,
  Z_FILTERED: 1,
  Z_HUFFMAN_ONLY: 2,
  Z_RLE: 3,
  Z_FIXED: 4,
  Z_DEFAULT_STRATEGY: 0,
  Z_BINARY: 0,
  Z_TEXT: 1,
  Z_UNKNOWN: 2,
  Z_DEFLATED: 8
}, { _tr_init: gi, _tr_stored_block: lt, _tr_flush_block: pi, _tr_tally: G, _tr_align: ki } = si, { Z_NO_FLUSH: j, Z_PARTIAL_FLUSH: Ei, Z_FULL_FLUSH: mi, Z_FINISH: C, Z_BLOCK: At, Z_OK: O, Z_STREAM_END: St, Z_STREAM_ERROR: H, Z_DATA_ERROR: yi, Z_BUF_ERROR: je, Z_DEFAULT_COMPRESSION: xi, Z_FILTERED: zi, Z_HUFFMAN_ONLY: De, Z_RLE: Ai, Z_FIXED: Si, Z_DEFAULT_STRATEGY: Ri, Z_UNKNOWN: Ti, Z_DEFLATED: He } = ie, Zi = 9, Di = 15, Oi = 8, Ii = 573, T = 3, J = 258, B = 262, Ni = 32, le = 42, vt = 57, _t = 69, ot = 73, ht = 91, ft = 103, Q = 113, ue = 666, I = 1, he = 2, te = 3, fe = 4, Li = 3, q = (e, a) => (e.msg = ee[a], a), Rt = (e) => e * 2 - (e > 4 ? 9 : 0), X = (e) => {
  let a = e.length;
  for (; --a >= 0; ) e[a] = 0;
}, Ui = (e) => {
  let a, t, i, r = e.w_size;
  a = e.hash_size, i = a;
  do
    t = e.head[--i], e.head[i] = t >= r ? t - r : 0;
  while (--a);
  a = r, i = a;
  do
    t = e.prev[--i], e.prev[i] = t >= r ? t - r : 0;
  while (--a);
}, Ci = (e, a, t) => (a << e.hash_shift ^ t) & e.hash_mask, W = Ci, L = (e) => {
  const a = e.state;
  let t = a.pending;
  t > e.avail_out && (t = e.avail_out), t !== 0 && (e.output.set(a.pending_buf.subarray(a.pending_out, a.pending_out + t), e.next_out), e.next_out += t, a.pending_out += t, e.total_out += t, e.avail_out -= t, a.pending -= t, a.pending === 0 && (a.pending_out = 0));
}, U = (e, a) => {
  pi(e, e.block_start >= 0 ? e.block_start : -1, e.strstart - e.block_start, a), e.block_start = e.strstart, L(e.strm);
}, x = (e, a) => {
  e.pending_buf[e.pending++] = a;
}, de = (e, a) => {
  e.pending_buf[e.pending++] = a >>> 8 & 255, e.pending_buf[e.pending++] = a & 255;
}, dt = (e, a, t, i) => {
  let r = e.avail_in;
  return r > i && (r = i), r === 0 ? 0 : (e.avail_in -= r, a.set(e.input.subarray(e.next_in, e.next_in + r), t), e.state.wrap === 1 ? e.adler = me(e.adler, a, r, t) : e.state.wrap === 2 && (e.adler = D(e.adler, a, r, t)), e.next_in += r, e.total_in += r, r);
}, ga = (e, a) => {
  let t = e.max_chain_length, i = e.strstart, r, n, o = e.prev_length, _ = e.nice_match;
  const s = e.strstart > e.w_size - B ? e.strstart - (e.w_size - B) : 0, l = e.window, h = e.w_mask, m = e.prev, u = e.strstart + J;
  let f = l[i + o - 1], c = l[i + o];
  e.prev_length >= e.good_match && (t >>= 2), _ > e.lookahead && (_ = e.lookahead);
  do
    if (r = a, !(l[r + o] !== c || l[r + o - 1] !== f || l[r] !== l[i] || l[++r] !== l[i + 1])) {
      i += 2, r++;
      do
        ;
      while (l[++i] === l[++r] && l[++i] === l[++r] && l[++i] === l[++r] && l[++i] === l[++r] && l[++i] === l[++r] && l[++i] === l[++r] && l[++i] === l[++r] && l[++i] === l[++r] && i < u);
      if (n = J - (u - i), i = u - J, n > o) {
        if (e.match_start = a, o = n, n >= _) break;
        f = l[i + o - 1], c = l[i + o];
      }
    }
  while ((a = m[a & h]) > s && --t !== 0);
  return o <= e.lookahead ? o : e.lookahead;
}, _e = (e) => {
  const a = e.w_size;
  let t, i, r;
  do {
    if (i = e.window_size - e.lookahead - e.strstart, e.strstart >= a + (a - B) && (e.window.set(e.window.subarray(a, a + a - i), 0), e.match_start -= a, e.strstart -= a, e.block_start -= a, e.insert > e.strstart && (e.insert = e.strstart), Ui(e), i += a), e.strm.avail_in === 0) break;
    if (t = dt(e.strm, e.window, e.strstart + e.lookahead, i), e.lookahead += t, e.lookahead + e.insert >= T)
      for (r = e.strstart - e.insert, e.ins_h = e.window[r], e.ins_h = W(e, e.ins_h, e.window[r + 1]); e.insert && (e.ins_h = W(e, e.ins_h, e.window[r + T - 1]), e.prev[r & e.w_mask] = e.head[e.ins_h], e.head[e.ins_h] = r, r++, e.insert--, !(e.lookahead + e.insert < T)); )
        ;
  } while (e.lookahead < B && e.strm.avail_in !== 0);
}, pa = (e, a) => {
  let t = e.pending_buf_size - 5 > e.w_size ? e.w_size : e.pending_buf_size - 5, i, r, n, o = 0, _ = e.strm.avail_in;
  do {
    if (i = 65535, n = e.bi_valid + 42 >> 3, e.strm.avail_out < n || (n = e.strm.avail_out - n, r = e.strstart - e.block_start, i > r + e.strm.avail_in && (i = r + e.strm.avail_in), i > n && (i = n), i < t && (i === 0 && a !== C || a === j || i !== r + e.strm.avail_in))) break;
    o = a === C && i === r + e.strm.avail_in ? 1 : 0, lt(e, 0, 0, o), e.pending_buf[e.pending - 4] = i, e.pending_buf[e.pending - 3] = i >> 8, e.pending_buf[e.pending - 2] = ~i, e.pending_buf[e.pending - 1] = ~i >> 8, L(e.strm), r && (r > i && (r = i), e.strm.output.set(e.window.subarray(e.block_start, e.block_start + r), e.strm.next_out), e.strm.next_out += r, e.strm.avail_out -= r, e.strm.total_out += r, e.block_start += r, i -= r), i && (dt(e.strm, e.strm.output, e.strm.next_out, i), e.strm.next_out += i, e.strm.avail_out -= i, e.strm.total_out += i);
  } while (o === 0);
  return _ -= e.strm.avail_in, _ && (_ >= e.w_size ? (e.matches = 2, e.window.set(e.strm.input.subarray(e.strm.next_in - e.w_size, e.strm.next_in), 0), e.strstart = e.w_size, e.insert = e.strstart) : (e.window_size - e.strstart <= _ && (e.strstart -= e.w_size, e.window.set(e.window.subarray(e.w_size, e.w_size + e.strstart), 0), e.matches < 2 && e.matches++, e.insert > e.strstart && (e.insert = e.strstart)), e.window.set(e.strm.input.subarray(e.strm.next_in - _, e.strm.next_in), e.strstart), e.strstart += _, e.insert += _ > e.w_size - e.insert ? e.w_size - e.insert : _), e.block_start = e.strstart), e.high_water < e.strstart && (e.high_water = e.strstart), o ? fe : a !== j && a !== C && e.strm.avail_in === 0 && e.strstart === e.block_start ? he : (n = e.window_size - e.strstart, e.strm.avail_in > n && e.block_start >= e.w_size && (e.block_start -= e.w_size, e.strstart -= e.w_size, e.window.set(e.window.subarray(e.w_size, e.w_size + e.strstart), 0), e.matches < 2 && e.matches++, n += e.w_size, e.insert > e.strstart && (e.insert = e.strstart)), n > e.strm.avail_in && (n = e.strm.avail_in), n && (dt(e.strm, e.window, e.strstart, n), e.strstart += n, e.insert += n > e.w_size - e.insert ? e.w_size - e.insert : n), e.high_water < e.strstart && (e.high_water = e.strstart), n = e.bi_valid + 42 >> 3, n = e.pending_buf_size - n > 65535 ? 65535 : e.pending_buf_size - n, t = n > e.w_size ? e.w_size : n, r = e.strstart - e.block_start, (r >= t || (r || a === C) && a !== j && e.strm.avail_in === 0 && r <= n) && (i = r > n ? n : r, o = a === C && e.strm.avail_in === 0 && i === r ? 1 : 0, lt(e, e.block_start, i, o), e.block_start += i, L(e.strm)), o ? te : I);
}, We = (e, a) => {
  let t, i;
  for (; ; ) {
    if (e.lookahead < B) {
      if (_e(e), e.lookahead < B && a === j) return I;
      if (e.lookahead === 0) break;
    }
    if (t = 0, e.lookahead >= T && (e.ins_h = W(e, e.ins_h, e.window[e.strstart + T - 1]), t = e.prev[e.strstart & e.w_mask] = e.head[e.ins_h], e.head[e.ins_h] = e.strstart), t !== 0 && e.strstart - t <= e.w_size - B && (e.match_length = ga(e, t)), e.match_length >= T)
      if (i = G(e, e.strstart - e.match_start, e.match_length - T), e.lookahead -= e.match_length, e.match_length <= e.max_lazy_match && e.lookahead >= T) {
        e.match_length--;
        do
          e.strstart++, e.ins_h = W(e, e.ins_h, e.window[e.strstart + T - 1]), t = e.prev[e.strstart & e.w_mask] = e.head[e.ins_h], e.head[e.ins_h] = e.strstart;
        while (--e.match_length !== 0);
        e.strstart++;
      } else
        e.strstart += e.match_length, e.match_length = 0, e.ins_h = e.window[e.strstart], e.ins_h = W(e, e.ins_h, e.window[e.strstart + 1]);
    else
      i = G(e, 0, e.window[e.strstart]), e.lookahead--, e.strstart++;
    if (i && (U(e, !1), e.strm.avail_out === 0))
      return I;
  }
  return e.insert = e.strstart < 2 ? e.strstart : 2, a === C ? (U(e, !0), e.strm.avail_out === 0 ? te : fe) : e.sym_next && (U(e, !1), e.strm.avail_out === 0) ? I : he;
}, re = (e, a) => {
  let t, i, r;
  for (; ; ) {
    if (e.lookahead < B) {
      if (_e(e), e.lookahead < B && a === j) return I;
      if (e.lookahead === 0) break;
    }
    if (t = 0, e.lookahead >= T && (e.ins_h = W(e, e.ins_h, e.window[e.strstart + T - 1]), t = e.prev[e.strstart & e.w_mask] = e.head[e.ins_h], e.head[e.ins_h] = e.strstart), e.prev_length = e.match_length, e.prev_match = e.match_start, e.match_length = 2, t !== 0 && e.prev_length < e.max_lazy_match && e.strstart - t <= e.w_size - B && (e.match_length = ga(e, t), e.match_length <= 5 && (e.strategy === zi || e.match_length === T && e.strstart - e.match_start > 4096) && (e.match_length = 2)), e.prev_length >= T && e.match_length <= e.prev_length) {
      r = e.strstart + e.lookahead - T, i = G(e, e.strstart - 1 - e.prev_match, e.prev_length - T), e.lookahead -= e.prev_length - 1, e.prev_length -= 2;
      do
        ++e.strstart <= r && (e.ins_h = W(e, e.ins_h, e.window[e.strstart + T - 1]), t = e.prev[e.strstart & e.w_mask] = e.head[e.ins_h], e.head[e.ins_h] = e.strstart);
      while (--e.prev_length !== 0);
      if (e.match_available = 0, e.match_length = 2, e.strstart++, i && (U(e, !1), e.strm.avail_out === 0))
        return I;
    } else if (e.match_available) {
      if (i = G(e, 0, e.window[e.strstart - 1]), i && U(e, !1), e.strstart++, e.lookahead--, e.strm.avail_out === 0) return I;
    } else
      e.match_available = 1, e.strstart++, e.lookahead--;
  }
  return e.match_available && (i = G(e, 0, e.window[e.strstart - 1]), e.match_available = 0), e.insert = e.strstart < 2 ? e.strstart : 2, a === C ? (U(e, !0), e.strm.avail_out === 0 ? te : fe) : e.sym_next && (U(e, !1), e.strm.avail_out === 0) ? I : he;
}, $i = (e, a) => {
  let t, i, r, n;
  const o = e.window;
  for (; ; ) {
    if (e.lookahead <= J) {
      if (_e(e), e.lookahead <= J && a === j) return I;
      if (e.lookahead === 0) break;
    }
    if (e.match_length = 0, e.lookahead >= T && e.strstart > 0 && (r = e.strstart - 1, i = o[r], i === o[++r] && i === o[++r] && i === o[++r])) {
      n = e.strstart + J;
      do
        ;
      while (i === o[++r] && i === o[++r] && i === o[++r] && i === o[++r] && i === o[++r] && i === o[++r] && i === o[++r] && i === o[++r] && r < n);
      e.match_length = J - (n - r), e.match_length > e.lookahead && (e.match_length = e.lookahead);
    }
    if (e.match_length >= T ? (t = G(e, 1, e.match_length - T), e.lookahead -= e.match_length, e.strstart += e.match_length, e.match_length = 0) : (t = G(e, 0, e.window[e.strstart]), e.lookahead--, e.strstart++), t && (U(e, !1), e.strm.avail_out === 0))
      return I;
  }
  return e.insert = 0, a === C ? (U(e, !0), e.strm.avail_out === 0 ? te : fe) : e.sym_next && (U(e, !1), e.strm.avail_out === 0) ? I : he;
}, Fi = (e, a) => {
  let t;
  for (; ; ) {
    if (e.lookahead === 0 && (_e(e), e.lookahead === 0)) {
      if (a === j) return I;
      break;
    }
    if (e.match_length = 0, t = G(e, 0, e.window[e.strstart]), e.lookahead--, e.strstart++, t && (U(e, !1), e.strm.avail_out === 0))
      return I;
  }
  return e.insert = 0, a === C ? (U(e, !0), e.strm.avail_out === 0 ? te : fe) : e.sym_next && (U(e, !1), e.strm.avail_out === 0) ? I : he;
};
function F(e, a, t, i, r) {
  this.good_length = e, this.max_lazy = a, this.nice_length = t, this.max_chain = i, this.func = r;
}
var se = [
  new F(0, 0, 0, 0, pa),
  new F(4, 4, 8, 4, We),
  new F(4, 5, 16, 8, We),
  new F(4, 6, 32, 32, We),
  new F(4, 4, 16, 16, re),
  new F(8, 16, 32, 32, re),
  new F(8, 16, 128, 128, re),
  new F(8, 32, 128, 256, re),
  new F(32, 128, 258, 1024, re),
  new F(32, 258, 258, 4096, re)
], Mi = (e) => {
  e.window_size = 2 * e.w_size, X(e.head), e.max_lazy_match = se[e.level].max_lazy, e.good_match = se[e.level].good_length, e.nice_match = se[e.level].nice_length, e.max_chain_length = se[e.level].max_chain, e.strstart = 0, e.block_start = 0, e.lookahead = 0, e.insert = 0, e.match_length = e.prev_length = 2, e.match_available = 0, e.ins_h = 0;
};
function Hi() {
  this.strm = null, this.status = 0, this.pending_buf = null, this.pending_buf_size = 0, this.pending_out = 0, this.pending = 0, this.wrap = 0, this.gzhead = null, this.gzindex = 0, this.method = He, this.last_flush = -1, this.w_size = 0, this.w_bits = 0, this.w_mask = 0, this.window = null, this.window_size = 0, this.prev = null, this.head = null, this.ins_h = 0, this.hash_size = 0, this.hash_bits = 0, this.hash_mask = 0, this.hash_shift = 0, this.block_start = 0, this.match_length = 0, this.prev_match = 0, this.match_available = 0, this.strstart = 0, this.match_start = 0, this.lookahead = 0, this.prev_length = 0, this.max_chain_length = 0, this.max_lazy_match = 0, this.level = 0, this.strategy = 0, this.good_match = 0, this.nice_match = 0, this.dyn_ltree = new Uint16Array(Ii * 2), this.dyn_dtree = /* @__PURE__ */ new Uint16Array(122), this.bl_tree = /* @__PURE__ */ new Uint16Array(78), X(this.dyn_ltree), X(this.dyn_dtree), X(this.bl_tree), this.l_desc = null, this.d_desc = null, this.bl_desc = null, this.bl_count = /* @__PURE__ */ new Uint16Array(16), this.heap = /* @__PURE__ */ new Uint16Array(573), X(this.heap), this.heap_len = 0, this.heap_max = 0, this.depth = /* @__PURE__ */ new Uint16Array(573), X(this.depth), this.sym_buf = 0, this.lit_bufsize = 0, this.sym_next = 0, this.sym_end = 0, this.opt_len = 0, this.static_len = 0, this.matches = 0, this.insert = 0, this.bi_buf = 0, this.bi_valid = 0;
}
var Ae = (e) => {
  if (!e) return 1;
  const a = e.state;
  return !a || a.strm !== e || a.status !== le && a.status !== vt && a.status !== _t && a.status !== ot && a.status !== ht && a.status !== ft && a.status !== Q && a.status !== ue ? 1 : 0;
}, ka = (e) => {
  if (Ae(e)) return q(e, H);
  e.total_in = e.total_out = 0, e.data_type = Ti;
  const a = e.state;
  return a.pending = 0, a.pending_out = 0, a.wrap < 0 && (a.wrap = -a.wrap), a.status = a.wrap === 2 ? vt : a.wrap ? le : Q, e.adler = a.wrap === 2 ? 0 : 1, a.last_flush = -2, gi(a), O;
}, Ea = (e) => {
  const a = ka(e);
  return a === O && Mi(e.state), a;
}, Bi = (e, a) => Ae(e) || e.state.wrap !== 2 ? H : (e.state.gzhead = a, O), ma = (e, a, t, i, r, n) => {
  if (!e) return H;
  let o = 1;
  if (a === xi && (a = 6), i < 0 ? (o = 0, i = -i) : i > 15 && (o = 2, i -= 16), r < 1 || r > Zi || t !== He || i < 8 || i > 15 || a < 0 || a > 9 || n < 0 || n > Si || i === 8 && o !== 1) return q(e, H);
  i === 8 && (i = 9);
  const _ = new Hi();
  return e.state = _, _.strm = e, _.status = le, _.wrap = o, _.gzhead = null, _.w_bits = i, _.w_size = 1 << _.w_bits, _.w_mask = _.w_size - 1, _.hash_bits = r + 7, _.hash_size = 1 << _.hash_bits, _.hash_mask = _.hash_size - 1, _.hash_shift = ~~((_.hash_bits + T - 1) / T), _.window = new Uint8Array(_.w_size * 2), _.head = new Uint16Array(_.hash_size), _.prev = new Uint16Array(_.w_size), _.lit_bufsize = 1 << r + 6, _.pending_buf_size = _.lit_bufsize * 4, _.pending_buf = new Uint8Array(_.pending_buf_size), _.sym_buf = _.lit_bufsize, _.sym_end = (_.lit_bufsize - 1) * 3, _.level = a, _.strategy = n, _.method = t, Ea(e);
}, Pi = (e, a) => ma(e, a, He, Di, Oi, Ri), Ki = (e, a) => {
  if (Ae(e) || a > At || a < 0) return e ? q(e, H) : H;
  const t = e.state;
  if (!e.output || e.avail_in !== 0 && !e.input || t.status === ue && a !== C) return q(e, e.avail_out === 0 ? je : H);
  const i = t.last_flush;
  if (t.last_flush = a, t.pending !== 0) {
    if (L(e), e.avail_out === 0)
      return t.last_flush = -1, O;
  } else if (e.avail_in === 0 && Rt(a) <= Rt(i) && a !== C) return q(e, je);
  if (t.status === ue && e.avail_in !== 0) return q(e, je);
  if (t.status === le && t.wrap === 0 && (t.status = Q), t.status === le) {
    let r = He + (t.w_bits - 8 << 4) << 8, n = -1;
    if (t.strategy >= De || t.level < 2 ? n = 0 : t.level < 6 ? n = 1 : t.level === 6 ? n = 2 : n = 3, r |= n << 6, t.strstart !== 0 && (r |= Ni), r += 31 - r % 31, de(t, r), t.strstart !== 0 && (de(t, e.adler >>> 16), de(t, e.adler & 65535)), e.adler = 1, t.status = Q, L(e), t.pending !== 0)
      return t.last_flush = -1, O;
  }
  if (t.status === vt) {
    if (e.adler = 0, x(t, 31), x(t, 139), x(t, 8), t.gzhead)
      x(t, (t.gzhead.text ? 1 : 0) + (t.gzhead.hcrc ? 2 : 0) + (t.gzhead.extra ? 4 : 0) + (t.gzhead.name ? 8 : 0) + (t.gzhead.comment ? 16 : 0)), x(t, t.gzhead.time & 255), x(t, t.gzhead.time >> 8 & 255), x(t, t.gzhead.time >> 16 & 255), x(t, t.gzhead.time >> 24 & 255), x(t, t.level === 9 ? 2 : t.strategy >= De || t.level < 2 ? 4 : 0), x(t, t.gzhead.os & 255), t.gzhead.extra && t.gzhead.extra.length && (x(t, t.gzhead.extra.length & 255), x(t, t.gzhead.extra.length >> 8 & 255)), t.gzhead.hcrc && (e.adler = D(e.adler, t.pending_buf, t.pending, 0)), t.gzindex = 0, t.status = _t;
    else if (x(t, 0), x(t, 0), x(t, 0), x(t, 0), x(t, 0), x(t, t.level === 9 ? 2 : t.strategy >= De || t.level < 2 ? 4 : 0), x(t, Li), t.status = Q, L(e), t.pending !== 0)
      return t.last_flush = -1, O;
  }
  if (t.status === _t) {
    if (t.gzhead.extra) {
      let r = t.pending, n = (t.gzhead.extra.length & 65535) - t.gzindex;
      for (; t.pending + n > t.pending_buf_size; ) {
        let _ = t.pending_buf_size - t.pending;
        if (t.pending_buf.set(t.gzhead.extra.subarray(t.gzindex, t.gzindex + _), t.pending), t.pending = t.pending_buf_size, t.gzhead.hcrc && t.pending > r && (e.adler = D(e.adler, t.pending_buf, t.pending - r, r)), t.gzindex += _, L(e), t.pending !== 0)
          return t.last_flush = -1, O;
        r = 0, n -= _;
      }
      let o = new Uint8Array(t.gzhead.extra);
      t.pending_buf.set(o.subarray(t.gzindex, t.gzindex + n), t.pending), t.pending += n, t.gzhead.hcrc && t.pending > r && (e.adler = D(e.adler, t.pending_buf, t.pending - r, r)), t.gzindex = 0;
    }
    t.status = ot;
  }
  if (t.status === ot) {
    if (t.gzhead.name) {
      let r = t.pending, n;
      do {
        if (t.pending === t.pending_buf_size) {
          if (t.gzhead.hcrc && t.pending > r && (e.adler = D(e.adler, t.pending_buf, t.pending - r, r)), L(e), t.pending !== 0)
            return t.last_flush = -1, O;
          r = 0;
        }
        t.gzindex < t.gzhead.name.length ? n = t.gzhead.name.charCodeAt(t.gzindex++) & 255 : n = 0, x(t, n);
      } while (n !== 0);
      t.gzhead.hcrc && t.pending > r && (e.adler = D(e.adler, t.pending_buf, t.pending - r, r)), t.gzindex = 0;
    }
    t.status = ht;
  }
  if (t.status === ht) {
    if (t.gzhead.comment) {
      let r = t.pending, n;
      do {
        if (t.pending === t.pending_buf_size) {
          if (t.gzhead.hcrc && t.pending > r && (e.adler = D(e.adler, t.pending_buf, t.pending - r, r)), L(e), t.pending !== 0)
            return t.last_flush = -1, O;
          r = 0;
        }
        t.gzindex < t.gzhead.comment.length ? n = t.gzhead.comment.charCodeAt(t.gzindex++) & 255 : n = 0, x(t, n);
      } while (n !== 0);
      t.gzhead.hcrc && t.pending > r && (e.adler = D(e.adler, t.pending_buf, t.pending - r, r));
    }
    t.status = ft;
  }
  if (t.status === ft) {
    if (t.gzhead.hcrc) {
      if (t.pending + 2 > t.pending_buf_size && (L(e), t.pending !== 0))
        return t.last_flush = -1, O;
      x(t, e.adler & 255), x(t, e.adler >> 8 & 255), e.adler = 0;
    }
    if (t.status = Q, L(e), t.pending !== 0)
      return t.last_flush = -1, O;
  }
  if (e.avail_in !== 0 || t.lookahead !== 0 || a !== j && t.status !== ue) {
    let r = t.level === 0 ? pa(t, a) : t.strategy === De ? Fi(t, a) : t.strategy === Ai ? $i(t, a) : se[t.level].func(t, a);
    if ((r === te || r === fe) && (t.status = ue), r === I || r === te)
      return e.avail_out === 0 && (t.last_flush = -1), O;
    if (r === he && (a === Ei ? ki(t) : a !== At && (lt(t, 0, 0, !1), a === mi && (X(t.head), t.lookahead === 0 && (t.strstart = 0, t.block_start = 0, t.insert = 0))), L(e), e.avail_out === 0))
      return t.last_flush = -1, O;
  }
  return a !== C ? O : t.wrap <= 0 ? St : (t.wrap === 2 ? (x(t, e.adler & 255), x(t, e.adler >> 8 & 255), x(t, e.adler >> 16 & 255), x(t, e.adler >> 24 & 255), x(t, e.total_in & 255), x(t, e.total_in >> 8 & 255), x(t, e.total_in >> 16 & 255), x(t, e.total_in >> 24 & 255)) : (de(t, e.adler >>> 16), de(t, e.adler & 65535)), L(e), t.wrap > 0 && (t.wrap = -t.wrap), t.pending !== 0 ? O : St);
}, Yi = (e) => {
  if (Ae(e)) return H;
  const a = e.state.status;
  return e.state = null, a === Q ? q(e, yi) : O;
}, Xi = (e, a) => {
  let t = a.length;
  if (Ae(e)) return H;
  const i = e.state, r = i.wrap;
  if (r === 2 || r === 1 && i.status !== le || i.lookahead) return H;
  if (r === 1 && (e.adler = me(e.adler, a, t, 0)), i.wrap = 0, t >= i.w_size) {
    r === 0 && (X(i.head), i.strstart = 0, i.block_start = 0, i.insert = 0);
    let s = new Uint8Array(i.w_size);
    s.set(a.subarray(t - i.w_size, t), 0), a = s, t = i.w_size;
  }
  const n = e.avail_in, o = e.next_in, _ = e.input;
  for (e.avail_in = t, e.next_in = 0, e.input = a, _e(i); i.lookahead >= T; ) {
    let s = i.strstart, l = i.lookahead - 2;
    do
      i.ins_h = W(i, i.ins_h, i.window[s + T - 1]), i.prev[s & i.w_mask] = i.head[i.ins_h], i.head[i.ins_h] = s, s++;
    while (--l);
    i.strstart = s, i.lookahead = 2, _e(i);
  }
  return i.strstart += i.lookahead, i.block_start = i.strstart, i.insert = i.lookahead, i.lookahead = 0, i.match_length = i.prev_length = 2, i.match_available = 0, e.next_in = o, e.input = _, e.avail_in = n, i.wrap = r, O;
}, be = {
  deflateInit: Pi,
  deflateInit2: ma,
  deflateReset: Ea,
  deflateResetKeep: ka,
  deflateSetHeader: Bi,
  deflate: Ki,
  deflateEnd: Yi,
  deflateSetDictionary: Xi,
  deflateInfo: "pako deflate (from Nodeca project)"
}, Gi = (e, a) => Object.prototype.hasOwnProperty.call(e, a), ji = function(e) {
  const a = Array.prototype.slice.call(arguments, 1);
  for (; a.length; ) {
    const t = a.shift();
    if (t) {
      if (typeof t != "object") throw new TypeError(t + "must be non-object");
      for (const i in t) Gi(t, i) && (e[i] = t[i]);
    }
  }
  return e;
}, Wi = (e) => {
  let a = 0;
  for (let i = 0, r = e.length; i < r; i++) a += e[i].length;
  const t = new Uint8Array(a);
  for (let i = 0, r = 0, n = e.length; i < n; i++) {
    let o = e[i];
    t.set(o, r), r += o.length;
  }
  return t;
}, Be = {
  assign: ji,
  flattenChunks: Wi
}, ya = !0;
try {
  String.fromCharCode.apply(null, /* @__PURE__ */ new Uint8Array(1));
} catch {
  ya = !1;
}
var ye = /* @__PURE__ */ new Uint8Array(256);
for (let e = 0; e < 256; e++) ye[e] = e >= 252 ? 6 : e >= 248 ? 5 : e >= 240 ? 4 : e >= 224 ? 3 : e >= 192 ? 2 : 1;
ye[254] = ye[254] = 1;
var Vi = (e) => {
  if (typeof TextEncoder == "function" && TextEncoder.prototype.encode) return new TextEncoder().encode(e);
  let a, t, i, r, n, o = e.length, _ = 0;
  for (r = 0; r < o; r++)
    t = e.charCodeAt(r), (t & 64512) === 55296 && r + 1 < o && (i = e.charCodeAt(r + 1), (i & 64512) === 56320 && (t = 65536 + (t - 55296 << 10) + (i - 56320), r++)), _ += t < 128 ? 1 : t < 2048 ? 2 : t < 65536 ? 3 : 4;
  for (a = new Uint8Array(_), n = 0, r = 0; n < _; r++)
    t = e.charCodeAt(r), (t & 64512) === 55296 && r + 1 < o && (i = e.charCodeAt(r + 1), (i & 64512) === 56320 && (t = 65536 + (t - 55296 << 10) + (i - 56320), r++)), t < 128 ? a[n++] = t : t < 2048 ? (a[n++] = 192 | t >>> 6, a[n++] = 128 | t & 63) : t < 65536 ? (a[n++] = 224 | t >>> 12, a[n++] = 128 | t >>> 6 & 63, a[n++] = 128 | t & 63) : (a[n++] = 240 | t >>> 18, a[n++] = 128 | t >>> 12 & 63, a[n++] = 128 | t >>> 6 & 63, a[n++] = 128 | t & 63);
  return a;
}, Ji = (e, a) => {
  if (a < 65534 && e.subarray && ya)
    return String.fromCharCode.apply(null, e.length === a ? e : e.subarray(0, a));
  let t = "";
  for (let i = 0; i < a; i++) t += String.fromCharCode(e[i]);
  return t;
}, Qi = (e, a) => {
  const t = a || e.length;
  if (typeof TextDecoder == "function" && TextDecoder.prototype.decode) return new TextDecoder().decode(e.subarray(0, a));
  let i, r;
  const n = new Array(t * 2);
  for (r = 0, i = 0; i < t; ) {
    let o = e[i++];
    if (o < 128) {
      n[r++] = o;
      continue;
    }
    let _ = ye[o];
    if (_ > 4) {
      n[r++] = 65533, i += _ - 1;
      continue;
    }
    for (o &= _ === 2 ? 31 : _ === 3 ? 15 : 7; _ > 1 && i < t; )
      o = o << 6 | e[i++] & 63, _--;
    if (_ > 1) {
      n[r++] = 65533;
      continue;
    }
    o < 65536 ? n[r++] = o : (o -= 65536, n[r++] = 55296 | o >> 10 & 1023, n[r++] = 56320 | o & 1023);
  }
  return Ji(n, r);
}, qi = (e, a) => {
  a = a || e.length, a > e.length && (a = e.length);
  let t = a - 1;
  for (; t >= 0 && (e[t] & 192) === 128; ) t--;
  return t < 0 || t === 0 ? a : t + ye[e[t]] > a ? t : a;
}, xe = {
  string2buf: Vi,
  buf2string: Qi,
  utf8border: qi
};
function en() {
  this.input = null, this.next_in = 0, this.avail_in = 0, this.total_in = 0, this.output = null, this.next_out = 0, this.avail_out = 0, this.total_out = 0, this.msg = "", this.state = null, this.data_type = 2, this.adler = 0;
}
var xa = en, za = Object.prototype.toString, { Z_NO_FLUSH: tn, Z_SYNC_FLUSH: an, Z_FULL_FLUSH: nn, Z_FINISH: rn, Z_OK: Fe, Z_STREAM_END: ln, Z_DEFAULT_COMPRESSION: _n, Z_DEFAULT_STRATEGY: on, Z_DEFLATED: hn } = ie;
function Se(e) {
  this.options = Be.assign({
    level: _n,
    method: hn,
    chunkSize: 16384,
    windowBits: 15,
    memLevel: 8,
    strategy: on
  }, e || {});
  let a = this.options;
  a.raw && a.windowBits > 0 ? a.windowBits = -a.windowBits : a.gzip && a.windowBits > 0 && a.windowBits < 16 && (a.windowBits += 16), this.err = 0, this.msg = "", this.ended = !1, this.chunks = [], this.strm = new xa(), this.strm.avail_out = 0;
  let t = be.deflateInit2(this.strm, a.level, a.method, a.windowBits, a.memLevel, a.strategy);
  if (t !== Fe) throw new Error(ee[t]);
  if (a.header && be.deflateSetHeader(this.strm, a.header), a.dictionary) {
    let i;
    if (typeof a.dictionary == "string" ? i = xe.string2buf(a.dictionary) : za.call(a.dictionary) === "[object ArrayBuffer]" ? i = new Uint8Array(a.dictionary) : i = a.dictionary, t = be.deflateSetDictionary(this.strm, i), t !== Fe) throw new Error(ee[t]);
    this._dict_set = !0;
  }
}
Se.prototype.push = function(e, a) {
  const t = this.strm, i = this.options.chunkSize;
  let r, n;
  if (this.ended) return !1;
  for (a === ~~a ? n = a : n = a === !0 ? rn : tn, typeof e == "string" ? t.input = xe.string2buf(e) : za.call(e) === "[object ArrayBuffer]" ? t.input = new Uint8Array(e) : t.input = e, t.next_in = 0, t.avail_in = t.input.length; ; ) {
    if (t.avail_out === 0 && (t.output = new Uint8Array(i), t.next_out = 0, t.avail_out = i), (n === an || n === nn) && t.avail_out <= 6) {
      this.onData(t.output.subarray(0, t.next_out)), t.avail_out = 0;
      continue;
    }
    if (r = be.deflate(t, n), r === ln)
      return t.next_out > 0 && this.onData(t.output.subarray(0, t.next_out)), r = be.deflateEnd(this.strm), this.onEnd(r), this.ended = !0, r === Fe;
    if (t.avail_out === 0) {
      this.onData(t.output);
      continue;
    }
    if (n > 0 && t.next_out > 0) {
      this.onData(t.output.subarray(0, t.next_out)), t.avail_out = 0;
      continue;
    }
    if (t.avail_in === 0) break;
  }
  return !0;
};
Se.prototype.onData = function(e) {
  this.chunks.push(e);
};
Se.prototype.onEnd = function(e) {
  e === Fe && (this.result = Be.flattenChunks(this.chunks)), this.chunks = [], this.err = e, this.msg = this.strm.msg;
};
function bt(e, a) {
  const t = new Se(a);
  if (t.push(e, !0), t.err) throw t.msg || ee[t.err];
  return t.result;
}
function fn(e, a) {
  return a = a || {}, a.raw = !0, bt(e, a);
}
function dn(e, a) {
  return a = a || {}, a.gzip = !0, bt(e, a);
}
var un = {
  Deflate: Se,
  deflate: bt,
  deflateRaw: fn,
  gzip: dn,
  constants: ie
}, Oe = 16209, sn = 16191, cn = function(a, t) {
  let i, r, n, o, _, s, l, h, m, u, f, c, R, k, b, z, g, d, y, Z, w, A, E, v;
  const p = a.state;
  i = a.next_in, E = a.input, r = i + (a.avail_in - 5), n = a.next_out, v = a.output, o = n - (t - a.avail_out), _ = n + (a.avail_out - 257), s = p.dmax, l = p.wsize, h = p.whave, m = p.wnext, u = p.window, f = p.hold, c = p.bits, R = p.lencode, k = p.distcode, b = (1 << p.lenbits) - 1, z = (1 << p.distbits) - 1;
  e: do {
    c < 15 && (f += E[i++] << c, c += 8, f += E[i++] << c, c += 8), g = R[f & b];
    t: for (; ; ) {
      if (d = g >>> 24, f >>>= d, c -= d, d = g >>> 16 & 255, d === 0) v[n++] = g & 65535;
      else if (d & 16) {
        y = g & 65535, d &= 15, d && (c < d && (f += E[i++] << c, c += 8), y += f & (1 << d) - 1, f >>>= d, c -= d), c < 15 && (f += E[i++] << c, c += 8, f += E[i++] << c, c += 8), g = k[f & z];
        a: for (; ; ) {
          if (d = g >>> 24, f >>>= d, c -= d, d = g >>> 16 & 255, d & 16) {
            if (Z = g & 65535, d &= 15, c < d && (f += E[i++] << c, c += 8, c < d && (f += E[i++] << c, c += 8)), Z += f & (1 << d) - 1, Z > s) {
              a.msg = "invalid distance too far back", p.mode = Oe;
              break e;
            }
            if (f >>>= d, c -= d, d = n - o, Z > d) {
              if (d = Z - d, d > h && p.sane) {
                a.msg = "invalid distance too far back", p.mode = Oe;
                break e;
              }
              if (w = 0, A = u, m === 0) {
                if (w += l - d, d < y) {
                  y -= d;
                  do
                    v[n++] = u[w++];
                  while (--d);
                  w = n - Z, A = v;
                }
              } else if (m < d) {
                if (w += l + m - d, d -= m, d < y) {
                  y -= d;
                  do
                    v[n++] = u[w++];
                  while (--d);
                  if (w = 0, m < y) {
                    d = m, y -= d;
                    do
                      v[n++] = u[w++];
                    while (--d);
                    w = n - Z, A = v;
                  }
                }
              } else if (w += m - d, d < y) {
                y -= d;
                do
                  v[n++] = u[w++];
                while (--d);
                w = n - Z, A = v;
              }
              for (; y > 2; )
                v[n++] = A[w++], v[n++] = A[w++], v[n++] = A[w++], y -= 3;
              y && (v[n++] = A[w++], y > 1 && (v[n++] = A[w++]));
            } else {
              w = n - Z;
              do
                v[n++] = v[w++], v[n++] = v[w++], v[n++] = v[w++], y -= 3;
              while (y > 2);
              y && (v[n++] = v[w++], y > 1 && (v[n++] = v[w++]));
            }
          } else if ((d & 64) === 0) {
            g = k[(g & 65535) + (f & (1 << d) - 1)];
            continue a;
          } else {
            a.msg = "invalid distance code", p.mode = Oe;
            break e;
          }
          break;
        }
      } else if ((d & 64) === 0) {
        g = R[(g & 65535) + (f & (1 << d) - 1)];
        continue t;
      } else if (d & 32) {
        p.mode = sn;
        break e;
      } else {
        a.msg = "invalid literal/length code", p.mode = Oe;
        break e;
      }
      break;
    }
  } while (i < r && n < _);
  y = c >> 3, i -= y, c -= y << 3, f &= (1 << c) - 1, a.next_in = i, a.next_out = n, a.avail_in = i < r ? 5 + (r - i) : 5 - (i - r), a.avail_out = n < _ ? 257 + (_ - n) : 257 - (n - _), p.hold = f, p.bits = c;
}, Ie = 15, Tt = 852, Zt = 592, Dt = 0, Ve = 1, Ot = 2, wn = new Uint16Array([
  3,
  4,
  5,
  6,
  7,
  8,
  9,
  10,
  11,
  13,
  15,
  17,
  19,
  23,
  27,
  31,
  35,
  43,
  51,
  59,
  67,
  83,
  99,
  115,
  131,
  163,
  195,
  227,
  258,
  0,
  0
]), vn = new Uint8Array([
  16,
  16,
  16,
  16,
  16,
  16,
  16,
  16,
  17,
  17,
  17,
  17,
  18,
  18,
  18,
  18,
  19,
  19,
  19,
  19,
  20,
  20,
  20,
  20,
  21,
  21,
  21,
  21,
  16,
  72,
  78
]), bn = new Uint16Array([
  1,
  2,
  3,
  4,
  5,
  7,
  9,
  13,
  17,
  25,
  33,
  49,
  65,
  97,
  129,
  193,
  257,
  385,
  513,
  769,
  1025,
  1537,
  2049,
  3073,
  4097,
  6145,
  8193,
  12289,
  16385,
  24577,
  0,
  0
]), gn = new Uint8Array([
  16,
  16,
  16,
  16,
  17,
  17,
  18,
  18,
  19,
  19,
  20,
  20,
  21,
  21,
  22,
  22,
  23,
  23,
  24,
  24,
  25,
  25,
  26,
  26,
  27,
  27,
  28,
  28,
  29,
  29,
  64,
  64
]), pn = (e, a, t, i, r, n, o, _) => {
  const s = _.bits;
  let l = 0, h = 0, m = 0, u = 0, f = 0, c = 0, R = 0, k = 0, b = 0, z = 0, g, d, y, Z, w, A = null, E;
  const v = /* @__PURE__ */ new Uint16Array(16), p = /* @__PURE__ */ new Uint16Array(16);
  let V = null, gt, Te, Ze;
  for (l = 0; l <= Ie; l++) v[l] = 0;
  for (h = 0; h < i; h++) v[a[t + h]]++;
  for (f = s, u = Ie; u >= 1 && v[u] === 0; u--) ;
  if (f > u && (f = u), u === 0)
    return r[n++] = 20971520, r[n++] = 20971520, _.bits = 1, 0;
  for (m = 1; m < u && v[m] === 0; m++) ;
  for (f < m && (f = m), k = 1, l = 1; l <= Ie; l++)
    if (k <<= 1, k -= v[l], k < 0) return -1;
  if (k > 0 && (e === Dt || u !== 1)) return -1;
  for (p[1] = 0, l = 1; l < Ie; l++) p[l + 1] = p[l] + v[l];
  for (h = 0; h < i; h++) a[t + h] !== 0 && (o[p[a[t + h]]++] = h);
  if (e === Dt ? (A = V = o, E = 20) : e === Ve ? (A = wn, V = vn, E = 257) : (A = bn, V = gn, E = 0), z = 0, h = 0, l = m, w = n, c = f, R = 0, y = -1, b = 1 << f, Z = b - 1, e === Ve && b > Tt || e === Ot && b > Zt) return 1;
  for (; ; ) {
    gt = l - R, o[h] + 1 < E ? (Te = 0, Ze = o[h]) : o[h] >= E ? (Te = V[o[h] - E], Ze = A[o[h] - E]) : (Te = 96, Ze = 0), g = 1 << l - R, d = 1 << c, m = d;
    do
      d -= g, r[w + (z >> R) + d] = gt << 24 | Te << 16 | Ze | 0;
    while (d !== 0);
    for (g = 1 << l - 1; z & g; ) g >>= 1;
    if (g !== 0 ? (z &= g - 1, z += g) : z = 0, h++, --v[l] === 0) {
      if (l === u) break;
      l = a[t + o[h]];
    }
    if (l > f && (z & Z) !== y) {
      for (R === 0 && (R = f), w += m, c = l - R, k = 1 << c; c + R < u && (k -= v[c + R], !(k <= 0)); )
        c++, k <<= 1;
      if (b += 1 << c, e === Ve && b > Tt || e === Ot && b > Zt) return 1;
      y = z & Z, r[y] = f << 24 | c << 16 | w - n | 0;
    }
  }
  return z !== 0 && (r[w + z] = l - R << 24 | 4194304), _.bits = f, 0;
}, ge = pn, kn = 0, Aa = 1, Sa = 2, { Z_FINISH: It, Z_BLOCK: En, Z_TREES: Ne, Z_OK: ae, Z_STREAM_END: mn, Z_NEED_DICT: yn, Z_STREAM_ERROR: $, Z_DATA_ERROR: Ra, Z_MEM_ERROR: Ta, Z_BUF_ERROR: xn, Z_DEFLATED: Nt } = ie, Pe = 16180, Lt = 16181, Ut = 16182, Ct = 16183, $t = 16184, Ft = 16185, Mt = 16186, Ht = 16187, Bt = 16188, Pt = 16189, Me = 16190, P = 16191, Je = 16192, Kt = 16193, Qe = 16194, Yt = 16195, Xt = 16196, Gt = 16197, jt = 16198, Le = 16199, Ue = 16200, Wt = 16201, Vt = 16202, Jt = 16203, Qt = 16204, qt = 16205, qe = 16206, ea = 16207, ta = 16208, S = 16209, Za = 16210, Da = 16211, zn = 852, An = 592, Sn = 15, aa = (e) => (e >>> 24 & 255) + (e >>> 8 & 65280) + ((e & 65280) << 8) + ((e & 255) << 24);
function Rn() {
  this.strm = null, this.mode = 0, this.last = !1, this.wrap = 0, this.havedict = !1, this.flags = 0, this.dmax = 0, this.check = 0, this.total = 0, this.head = null, this.wbits = 0, this.wsize = 0, this.whave = 0, this.wnext = 0, this.window = null, this.hold = 0, this.bits = 0, this.length = 0, this.offset = 0, this.extra = 0, this.lencode = null, this.distcode = null, this.lenbits = 0, this.distbits = 0, this.ncode = 0, this.nlen = 0, this.ndist = 0, this.have = 0, this.next = null, this.lens = /* @__PURE__ */ new Uint16Array(320), this.work = /* @__PURE__ */ new Uint16Array(288), this.lendyn = null, this.distdyn = null, this.sane = 0, this.back = 0, this.was = 0;
}
var ne = (e) => {
  if (!e) return 1;
  const a = e.state;
  return !a || a.strm !== e || a.mode < Pe || a.mode > Da ? 1 : 0;
}, Oa = (e) => {
  if (ne(e)) return $;
  const a = e.state;
  return e.total_in = e.total_out = a.total = 0, e.msg = "", a.wrap && (e.adler = a.wrap & 1), a.mode = Pe, a.last = 0, a.havedict = 0, a.flags = -1, a.dmax = 32768, a.head = null, a.hold = 0, a.bits = 0, a.lencode = a.lendyn = new Int32Array(zn), a.distcode = a.distdyn = new Int32Array(An), a.sane = 1, a.back = -1, ae;
}, Ia = (e) => {
  if (ne(e)) return $;
  const a = e.state;
  return a.wsize = 0, a.whave = 0, a.wnext = 0, Oa(e);
}, Na = (e, a) => {
  let t;
  if (ne(e)) return $;
  const i = e.state;
  return a < 0 ? (t = 0, a = -a) : (t = (a >> 4) + 5, a < 48 && (a &= 15)), a && (a < 8 || a > 15) ? $ : (i.window !== null && i.wbits !== a && (i.window = null), i.wrap = t, i.wbits = a, Ia(e));
}, La = (e, a) => {
  if (!e) return $;
  const t = new Rn();
  e.state = t, t.strm = e, t.window = null, t.mode = Pe;
  const i = Na(e, a);
  return i !== ae && (e.state = null), i;
}, Tn = (e) => La(e, Sn), ia = !0, et, tt, Zn = (e) => {
  if (ia) {
    et = /* @__PURE__ */ new Int32Array(512), tt = /* @__PURE__ */ new Int32Array(32);
    let a = 0;
    for (; a < 144; ) e.lens[a++] = 8;
    for (; a < 256; ) e.lens[a++] = 9;
    for (; a < 280; ) e.lens[a++] = 7;
    for (; a < 288; ) e.lens[a++] = 8;
    for (ge(Aa, e.lens, 0, 288, et, 0, e.work, { bits: 9 }), a = 0; a < 32; ) e.lens[a++] = 5;
    ge(Sa, e.lens, 0, 32, tt, 0, e.work, { bits: 5 }), ia = !1;
  }
  e.lencode = et, e.lenbits = 9, e.distcode = tt, e.distbits = 5;
}, Ua = (e, a, t, i) => {
  let r;
  const n = e.state;
  return n.window === null && (n.wsize = 1 << n.wbits, n.wnext = 0, n.whave = 0, n.window = new Uint8Array(n.wsize)), i >= n.wsize ? (n.window.set(a.subarray(t - n.wsize, t), 0), n.wnext = 0, n.whave = n.wsize) : (r = n.wsize - n.wnext, r > i && (r = i), n.window.set(a.subarray(t - i, t - i + r), n.wnext), i -= r, i ? (n.window.set(a.subarray(t - i, t), 0), n.wnext = i, n.whave = n.wsize) : (n.wnext += r, n.wnext === n.wsize && (n.wnext = 0), n.whave < n.wsize && (n.whave += r))), 0;
}, Dn = (e, a) => {
  let t, i, r, n, o, _, s, l, h, m, u, f, c, R, k = 0, b, z, g, d, y, Z, w, A;
  const E = /* @__PURE__ */ new Uint8Array(4);
  let v, p;
  const V = new Uint8Array([
    16,
    17,
    18,
    0,
    8,
    7,
    9,
    6,
    10,
    5,
    11,
    4,
    12,
    3,
    13,
    2,
    14,
    1,
    15
  ]);
  if (ne(e) || !e.output || !e.input && e.avail_in !== 0) return $;
  t = e.state, t.mode === P && (t.mode = Je), o = e.next_out, r = e.output, s = e.avail_out, n = e.next_in, i = e.input, _ = e.avail_in, l = t.hold, h = t.bits, m = _, u = s, A = ae;
  e: for (; ; ) switch (t.mode) {
    case Pe:
      if (t.wrap === 0) {
        t.mode = Je;
        break;
      }
      for (; h < 16; ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      if (t.wrap & 2 && l === 35615) {
        t.wbits === 0 && (t.wbits = 15), t.check = 0, E[0] = l & 255, E[1] = l >>> 8 & 255, t.check = D(t.check, E, 2, 0), l = 0, h = 0, t.mode = Lt;
        break;
      }
      if (t.head && (t.head.done = !1), !(t.wrap & 1) || (((l & 255) << 8) + (l >> 8)) % 31) {
        e.msg = "incorrect header check", t.mode = S;
        break;
      }
      if ((l & 15) !== Nt) {
        e.msg = "unknown compression method", t.mode = S;
        break;
      }
      if (l >>>= 4, h -= 4, w = (l & 15) + 8, t.wbits === 0 && (t.wbits = w), w > 15 || w > t.wbits) {
        e.msg = "invalid window size", t.mode = S;
        break;
      }
      t.dmax = 1 << t.wbits, t.flags = 0, e.adler = t.check = 1, t.mode = l & 512 ? Pt : P, l = 0, h = 0;
      break;
    case Lt:
      for (; h < 16; ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      if (t.flags = l, (t.flags & 255) !== Nt) {
        e.msg = "unknown compression method", t.mode = S;
        break;
      }
      if (t.flags & 57344) {
        e.msg = "unknown header flags set", t.mode = S;
        break;
      }
      t.head && (t.head.text = l >> 8 & 1), t.flags & 512 && t.wrap & 4 && (E[0] = l & 255, E[1] = l >>> 8 & 255, t.check = D(t.check, E, 2, 0)), l = 0, h = 0, t.mode = Ut;
    case Ut:
      for (; h < 32; ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      t.head && (t.head.time = l), t.flags & 512 && t.wrap & 4 && (E[0] = l & 255, E[1] = l >>> 8 & 255, E[2] = l >>> 16 & 255, E[3] = l >>> 24 & 255, t.check = D(t.check, E, 4, 0)), l = 0, h = 0, t.mode = Ct;
    case Ct:
      for (; h < 16; ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      t.head && (t.head.xflags = l & 255, t.head.os = l >> 8), t.flags & 512 && t.wrap & 4 && (E[0] = l & 255, E[1] = l >>> 8 & 255, t.check = D(t.check, E, 2, 0)), l = 0, h = 0, t.mode = $t;
    case $t:
      if (t.flags & 1024) {
        for (; h < 16; ) {
          if (_ === 0) break e;
          _--, l += i[n++] << h, h += 8;
        }
        t.length = l, t.head && (t.head.extra_len = l), t.flags & 512 && t.wrap & 4 && (E[0] = l & 255, E[1] = l >>> 8 & 255, t.check = D(t.check, E, 2, 0)), l = 0, h = 0;
      } else t.head && (t.head.extra = null);
      t.mode = Ft;
    case Ft:
      if (t.flags & 1024 && (f = t.length, f > _ && (f = _), f && (t.head && (w = t.head.extra_len - t.length, t.head.extra || (t.head.extra = new Uint8Array(t.head.extra_len)), t.head.extra.set(i.subarray(n, n + f), w)), t.flags & 512 && t.wrap & 4 && (t.check = D(t.check, i, f, n)), _ -= f, n += f, t.length -= f), t.length))
        break e;
      t.length = 0, t.mode = Mt;
    case Mt:
      if (t.flags & 2048) {
        if (_ === 0) break e;
        f = 0;
        do
          w = i[n + f++], t.head && w && t.length < 65536 && (t.head.name += String.fromCharCode(w));
        while (w && f < _);
        if (t.flags & 512 && t.wrap & 4 && (t.check = D(t.check, i, f, n)), _ -= f, n += f, w) break e;
      } else t.head && (t.head.name = null);
      t.length = 0, t.mode = Ht;
    case Ht:
      if (t.flags & 4096) {
        if (_ === 0) break e;
        f = 0;
        do
          w = i[n + f++], t.head && w && t.length < 65536 && (t.head.comment += String.fromCharCode(w));
        while (w && f < _);
        if (t.flags & 512 && t.wrap & 4 && (t.check = D(t.check, i, f, n)), _ -= f, n += f, w) break e;
      } else t.head && (t.head.comment = null);
      t.mode = Bt;
    case Bt:
      if (t.flags & 512) {
        for (; h < 16; ) {
          if (_ === 0) break e;
          _--, l += i[n++] << h, h += 8;
        }
        if (t.wrap & 4 && l !== (t.check & 65535)) {
          e.msg = "header crc mismatch", t.mode = S;
          break;
        }
        l = 0, h = 0;
      }
      t.head && (t.head.hcrc = t.flags >> 9 & 1, t.head.done = !0), e.adler = t.check = 0, t.mode = P;
      break;
    case Pt:
      for (; h < 32; ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      e.adler = t.check = aa(l), l = 0, h = 0, t.mode = Me;
    case Me:
      if (t.havedict === 0)
        return e.next_out = o, e.avail_out = s, e.next_in = n, e.avail_in = _, t.hold = l, t.bits = h, yn;
      e.adler = t.check = 1, t.mode = P;
    case P:
      if (a === En || a === Ne) break e;
    case Je:
      if (t.last) {
        l >>>= h & 7, h -= h & 7, t.mode = qe;
        break;
      }
      for (; h < 3; ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      switch (t.last = l & 1, l >>>= 1, h -= 1, l & 3) {
        case 0:
          t.mode = Kt;
          break;
        case 1:
          if (Zn(t), t.mode = Le, a === Ne) {
            l >>>= 2, h -= 2;
            break e;
          }
          break;
        case 2:
          t.mode = Xt;
          break;
        case 3:
          e.msg = "invalid block type", t.mode = S;
      }
      l >>>= 2, h -= 2;
      break;
    case Kt:
      for (l >>>= h & 7, h -= h & 7; h < 32; ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      if ((l & 65535) !== (l >>> 16 ^ 65535)) {
        e.msg = "invalid stored block lengths", t.mode = S;
        break;
      }
      if (t.length = l & 65535, l = 0, h = 0, t.mode = Qe, a === Ne) break e;
    case Qe:
      t.mode = Yt;
    case Yt:
      if (f = t.length, f) {
        if (f > _ && (f = _), f > s && (f = s), f === 0) break e;
        r.set(i.subarray(n, n + f), o), _ -= f, n += f, s -= f, o += f, t.length -= f;
        break;
      }
      t.mode = P;
      break;
    case Xt:
      for (; h < 14; ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      if (t.nlen = (l & 31) + 257, l >>>= 5, h -= 5, t.ndist = (l & 31) + 1, l >>>= 5, h -= 5, t.ncode = (l & 15) + 4, l >>>= 4, h -= 4, t.nlen > 286 || t.ndist > 30) {
        e.msg = "too many length or distance symbols", t.mode = S;
        break;
      }
      t.have = 0, t.mode = Gt;
    case Gt:
      for (; t.have < t.ncode; ) {
        for (; h < 3; ) {
          if (_ === 0) break e;
          _--, l += i[n++] << h, h += 8;
        }
        t.lens[V[t.have++]] = l & 7, l >>>= 3, h -= 3;
      }
      for (; t.have < 19; ) t.lens[V[t.have++]] = 0;
      if (t.lencode = t.lendyn, t.lenbits = 7, v = { bits: t.lenbits }, A = ge(kn, t.lens, 0, 19, t.lencode, 0, t.work, v), t.lenbits = v.bits, A) {
        e.msg = "invalid code lengths set", t.mode = S;
        break;
      }
      t.have = 0, t.mode = jt;
    case jt:
      for (; t.have < t.nlen + t.ndist; ) {
        for (; k = t.lencode[l & (1 << t.lenbits) - 1], b = k >>> 24, z = k >>> 16 & 255, g = k & 65535, !(b <= h); ) {
          if (_ === 0) break e;
          _--, l += i[n++] << h, h += 8;
        }
        if (g < 16)
          l >>>= b, h -= b, t.lens[t.have++] = g;
        else {
          if (g === 16) {
            for (p = b + 2; h < p; ) {
              if (_ === 0) break e;
              _--, l += i[n++] << h, h += 8;
            }
            if (l >>>= b, h -= b, t.have === 0) {
              e.msg = "invalid bit length repeat", t.mode = S;
              break;
            }
            w = t.lens[t.have - 1], f = 3 + (l & 3), l >>>= 2, h -= 2;
          } else if (g === 17) {
            for (p = b + 3; h < p; ) {
              if (_ === 0) break e;
              _--, l += i[n++] << h, h += 8;
            }
            l >>>= b, h -= b, w = 0, f = 3 + (l & 7), l >>>= 3, h -= 3;
          } else {
            for (p = b + 7; h < p; ) {
              if (_ === 0) break e;
              _--, l += i[n++] << h, h += 8;
            }
            l >>>= b, h -= b, w = 0, f = 11 + (l & 127), l >>>= 7, h -= 7;
          }
          if (t.have + f > t.nlen + t.ndist) {
            e.msg = "invalid bit length repeat", t.mode = S;
            break;
          }
          for (; f--; ) t.lens[t.have++] = w;
        }
      }
      if (t.mode === S) break;
      if (t.lens[256] === 0) {
        e.msg = "invalid code -- missing end-of-block", t.mode = S;
        break;
      }
      if (t.lenbits = 9, v = { bits: t.lenbits }, A = ge(Aa, t.lens, 0, t.nlen, t.lencode, 0, t.work, v), t.lenbits = v.bits, A) {
        e.msg = "invalid literal/lengths set", t.mode = S;
        break;
      }
      if (t.distbits = 6, t.distcode = t.distdyn, v = { bits: t.distbits }, A = ge(Sa, t.lens, t.nlen, t.ndist, t.distcode, 0, t.work, v), t.distbits = v.bits, A) {
        e.msg = "invalid distances set", t.mode = S;
        break;
      }
      if (t.mode = Le, a === Ne) break e;
    case Le:
      t.mode = Ue;
    case Ue:
      if (_ >= 6 && s >= 258) {
        e.next_out = o, e.avail_out = s, e.next_in = n, e.avail_in = _, t.hold = l, t.bits = h, cn(e, u), o = e.next_out, r = e.output, s = e.avail_out, n = e.next_in, i = e.input, _ = e.avail_in, l = t.hold, h = t.bits, t.mode === P && (t.back = -1);
        break;
      }
      for (t.back = 0; k = t.lencode[l & (1 << t.lenbits) - 1], b = k >>> 24, z = k >>> 16 & 255, g = k & 65535, !(b <= h); ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      if (z && (z & 240) === 0) {
        for (d = b, y = z, Z = g; k = t.lencode[Z + ((l & (1 << d + y) - 1) >> d)], b = k >>> 24, z = k >>> 16 & 255, g = k & 65535, !(d + b <= h); ) {
          if (_ === 0) break e;
          _--, l += i[n++] << h, h += 8;
        }
        l >>>= d, h -= d, t.back += d;
      }
      if (l >>>= b, h -= b, t.back += b, t.length = g, z === 0) {
        t.mode = qt;
        break;
      }
      if (z & 32) {
        t.back = -1, t.mode = P;
        break;
      }
      if (z & 64) {
        e.msg = "invalid literal/length code", t.mode = S;
        break;
      }
      t.extra = z & 15, t.mode = Wt;
    case Wt:
      if (t.extra) {
        for (p = t.extra; h < p; ) {
          if (_ === 0) break e;
          _--, l += i[n++] << h, h += 8;
        }
        t.length += l & (1 << t.extra) - 1, l >>>= t.extra, h -= t.extra, t.back += t.extra;
      }
      t.was = t.length, t.mode = Vt;
    case Vt:
      for (; k = t.distcode[l & (1 << t.distbits) - 1], b = k >>> 24, z = k >>> 16 & 255, g = k & 65535, !(b <= h); ) {
        if (_ === 0) break e;
        _--, l += i[n++] << h, h += 8;
      }
      if ((z & 240) === 0) {
        for (d = b, y = z, Z = g; k = t.distcode[Z + ((l & (1 << d + y) - 1) >> d)], b = k >>> 24, z = k >>> 16 & 255, g = k & 65535, !(d + b <= h); ) {
          if (_ === 0) break e;
          _--, l += i[n++] << h, h += 8;
        }
        l >>>= d, h -= d, t.back += d;
      }
      if (l >>>= b, h -= b, t.back += b, z & 64) {
        e.msg = "invalid distance code", t.mode = S;
        break;
      }
      t.offset = g, t.extra = z & 15, t.mode = Jt;
    case Jt:
      if (t.extra) {
        for (p = t.extra; h < p; ) {
          if (_ === 0) break e;
          _--, l += i[n++] << h, h += 8;
        }
        t.offset += l & (1 << t.extra) - 1, l >>>= t.extra, h -= t.extra, t.back += t.extra;
      }
      if (t.offset > t.dmax) {
        e.msg = "invalid distance too far back", t.mode = S;
        break;
      }
      t.mode = Qt;
    case Qt:
      if (s === 0) break e;
      if (f = u - s, t.offset > f) {
        if (f = t.offset - f, f > t.whave && t.sane) {
          e.msg = "invalid distance too far back", t.mode = S;
          break;
        }
        f > t.wnext ? (f -= t.wnext, c = t.wsize - f) : c = t.wnext - f, f > t.length && (f = t.length), R = t.window;
      } else
        R = r, c = o - t.offset, f = t.length;
      f > s && (f = s), s -= f, t.length -= f;
      do
        r[o++] = R[c++];
      while (--f);
      t.length === 0 && (t.mode = Ue);
      break;
    case qt:
      if (s === 0) break e;
      r[o++] = t.length, s--, t.mode = Ue;
      break;
    case qe:
      if (t.wrap) {
        for (; h < 32; ) {
          if (_ === 0) break e;
          _--, l |= i[n++] << h, h += 8;
        }
        if (u -= s, e.total_out += u, t.total += u, t.wrap & 4 && u && (e.adler = t.check = t.flags ? D(t.check, r, u, o - u) : me(t.check, r, u, o - u)), u = s, t.wrap & 4 && (t.flags ? l : aa(l)) !== t.check) {
          e.msg = "incorrect data check", t.mode = S;
          break;
        }
        l = 0, h = 0;
      }
      t.mode = ea;
    case ea:
      if (t.wrap && t.flags) {
        for (; h < 32; ) {
          if (_ === 0) break e;
          _--, l += i[n++] << h, h += 8;
        }
        if (t.wrap & 4 && l !== (t.total & 4294967295)) {
          e.msg = "incorrect length check", t.mode = S;
          break;
        }
        l = 0, h = 0;
      }
      t.mode = ta;
    case ta:
      A = mn;
      break e;
    case S:
      A = Ra;
      break e;
    case Za:
      return Ta;
    case Da:
    default:
      return $;
  }
  return e.next_out = o, e.avail_out = s, e.next_in = n, e.avail_in = _, t.hold = l, t.bits = h, (t.wsize || u !== e.avail_out && t.mode < S && (t.mode < qe || a !== It)) && Ua(e, e.output, e.next_out, u - e.avail_out), m -= e.avail_in, u -= e.avail_out, e.total_in += m, e.total_out += u, t.total += u, t.wrap & 4 && u && (e.adler = t.check = t.flags ? D(t.check, r, u, e.next_out - u) : me(t.check, r, u, e.next_out - u)), e.data_type = t.bits + (t.last ? 64 : 0) + (t.mode === P ? 128 : 0) + (t.mode === Le || t.mode === Qe ? 256 : 0), (m === 0 && u === 0 || a === It) && A === ae && (A = xn), A;
}, On = (e) => {
  if (ne(e)) return $;
  let a = e.state;
  return a.window && (a.window = null), e.state = null, ae;
}, In = (e, a) => {
  if (ne(e)) return $;
  const t = e.state;
  return (t.wrap & 2) === 0 ? $ : (t.head = a, a.done = !1, ae);
}, Nn = (e, a) => {
  const t = a.length;
  let i, r, n;
  return ne(e) || (i = e.state, i.wrap !== 0 && i.mode !== Me) ? $ : i.mode === Me && (r = 1, r = me(r, a, t, 0), r !== i.check) ? Ra : (n = Ua(e, a, t, t), n ? (i.mode = Za, Ta) : (i.havedict = 1, ae));
}, Y = {
  inflateReset: Ia,
  inflateReset2: Na,
  inflateResetKeep: Oa,
  inflateInit: Tn,
  inflateInit2: La,
  inflate: Dn,
  inflateEnd: On,
  inflateGetHeader: In,
  inflateSetDictionary: Nn,
  inflateInfo: "pako inflate (from Nodeca project)"
};
function Ln() {
  this.text = 0, this.time = 0, this.xflags = 0, this.os = 0, this.extra = null, this.extra_len = 0, this.name = "", this.comment = "", this.hcrc = 0, this.done = !1;
}
var Un = Ln, Ca = Object.prototype.toString, { Z_NO_FLUSH: Cn, Z_FINISH: $n, Z_OK: ze, Z_STREAM_END: at, Z_NEED_DICT: it, Z_STREAM_ERROR: Fn, Z_DATA_ERROR: na, Z_MEM_ERROR: Mn } = ie;
function Re(e) {
  this.options = Be.assign({
    chunkSize: 65536,
    windowBits: 15,
    to: ""
  }, e || {});
  const a = this.options;
  a.raw && a.windowBits >= 0 && a.windowBits < 16 && (a.windowBits = -a.windowBits, a.windowBits === 0 && (a.windowBits = -15)), a.windowBits >= 0 && a.windowBits < 16 && !(e && e.windowBits) && (a.windowBits += 32), a.windowBits > 15 && a.windowBits < 48 && (a.windowBits & 15) === 0 && (a.windowBits |= 15), this.err = 0, this.msg = "", this.ended = !1, this.chunks = [], this.strm = new xa(), this.strm.avail_out = 0;
  let t = Y.inflateInit2(this.strm, a.windowBits);
  if (t !== ze) throw new Error(ee[t]);
  if (this.header = new Un(), Y.inflateGetHeader(this.strm, this.header), a.dictionary && (typeof a.dictionary == "string" ? a.dictionary = xe.string2buf(a.dictionary) : Ca.call(a.dictionary) === "[object ArrayBuffer]" && (a.dictionary = new Uint8Array(a.dictionary)), a.raw && (t = Y.inflateSetDictionary(this.strm, a.dictionary), t !== ze)))
    throw new Error(ee[t]);
}
Re.prototype.push = function(e, a) {
  const t = this.strm, i = this.options.chunkSize, r = this.options.dictionary;
  let n, o, _;
  if (this.ended) return !1;
  for (a === ~~a ? o = a : o = a === !0 ? $n : Cn, Ca.call(e) === "[object ArrayBuffer]" ? t.input = new Uint8Array(e) : t.input = e, t.next_in = 0, t.avail_in = t.input.length; ; ) {
    for (t.avail_out === 0 && (t.output = new Uint8Array(i), t.next_out = 0, t.avail_out = i), n = Y.inflate(t, o), n === it && r && (n = Y.inflateSetDictionary(t, r), n === ze ? n = Y.inflate(t, o) : n === na && (n = it)); t.avail_in > 0 && n === at && t.state.wrap > 0 && e[t.next_in] !== 0; )
      Y.inflateReset(t), n = Y.inflate(t, o);
    switch (n) {
      case Fn:
      case na:
      case it:
      case Mn:
        return this.onEnd(n), this.ended = !0, !1;
    }
    if (_ = t.avail_out, t.next_out && (t.avail_out === 0 || n === at))
      if (this.options.to === "string") {
        let s = xe.utf8border(t.output, t.next_out), l = t.next_out - s, h = xe.buf2string(t.output, s);
        t.next_out = l, t.avail_out = i - l, l && t.output.set(t.output.subarray(s, s + l), 0), this.onData(h);
      } else this.onData(t.output.length === t.next_out ? t.output : t.output.subarray(0, t.next_out));
    if (!(n === ze && _ === 0)) {
      if (n === at)
        return n = Y.inflateEnd(this.strm), this.onEnd(n), this.ended = !0, !0;
      if (t.avail_in === 0) break;
    }
  }
  return !0;
};
Re.prototype.onData = function(e) {
  this.chunks.push(e);
};
Re.prototype.onEnd = function(e) {
  e === ze && (this.options.to === "string" ? this.result = this.chunks.join("") : this.result = Be.flattenChunks(this.chunks)), this.chunks = [], this.err = e, this.msg = this.strm.msg;
};
function ut(e, a) {
  const t = new Re(a);
  if (t.push(e), t.err) throw t.msg || ee[t.err];
  return t.result;
}
function Hn(e, a) {
  return a = a || {}, a.raw = !0, ut(e, a);
}
var Bn = {
  Inflate: Re,
  inflate: ut,
  inflateRaw: Hn,
  ungzip: ut,
  constants: ie
}, { Deflate: Pn, deflate: Kn, deflateRaw: Yn, gzip: Xn } = un, { Inflate: Gn, inflate: jn, inflateRaw: Wn, ungzip: Vn } = Bn, $a = Pn, Fa = Kn, Ma = Yn, Ha = Xn, Ba = Gn, Pa = jn, Ka = Wn, Ya = Vn, Xa = ie, Jn = {
  Deflate: $a,
  deflate: Fa,
  deflateRaw: Ma,
  gzip: Ha,
  Inflate: Ba,
  inflate: Pa,
  inflateRaw: Ka,
  ungzip: Ya,
  constants: Xa
};
export {
  qn as n,
  Ya as r,
  Jn as t
};

//# sourceMappingURL=pako.esm-CwBHObRx.js.map