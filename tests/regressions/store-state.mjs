import assert from 'node:assert/strict'
import { readCart, saveCart, readOrderAccess, saveOrderAccess } from '../../resources/js/lab/widget-runtime/storeState.js'
const data = new Map(), storage = {getItem:key=>data.get(key),setItem:(key,value)=>data.set(key,value),removeItem:key=>data.delete(key)}
const module='10000000-0000-4000-8000-000000000001', other='10000000-0000-4000-8000-000000000002', product='20000000-0000-4000-8000-000000000001'
saveCart(storage,module,{[product]:2}); assert.deepEqual(readCart(storage,module),{[product]:2}); assert.deepEqual(readCart(storage,other),{})
saveCart(storage,module,{[product]:-1,email:'private@example.invalid'}); assert.deepEqual(readCart(storage,module),{})
const now=Date.now(); saveOrderAccess(storage,module,{uuid:product,created_at:new Date(now).toISOString(),customer_name:'Never persist',slip:'Never persist'},'a'.repeat(64))
assert.equal(readOrderAccess(storage,module,now).uuid,product); assert.equal(readOrderAccess(storage,other,now),null)
assert.equal(readOrderAccess(storage,module,now+25*60*60*1000),null)
assert.ok(![...data.values()].join('').includes('Never persist'))
saveOrderAccess(storage,module,null,''); assert.equal(readOrderAccess(storage,module,now),null)
assert.deepEqual(readCart({getItem(){throw Error('Storage blocked')}},module),{})
console.log('PASS: module-scoped cart and session-only order capability storage (9 checks).')
