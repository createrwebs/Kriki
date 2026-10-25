import assert from 'node:assert/strict'
import fs from 'node:fs/promises'
import path from 'node:path'
import { pathToFileURL } from 'node:url'
import { createRequire } from 'node:module'
import { build } from 'esbuild'

const deps = path.resolve('.agent-work/test-deps')
const require = createRequire(path.join(deps,'package.json'))
const React = require('react')
const { create, act } = require('react-test-renderer')
globalThis.IS_REACT_ACT_ENVIRONMENT = true
const output = path.join(deps,'modules-prepare-test.mjs')
await build({ entryPoints:['resources/js/lab/components/modules/ModulesDesk.jsx'], outfile:output, bundle:true, format:'esm', jsx:'automatic', external:['react','react/jsx-runtime'], plugins:[{
    name:'owner-api-fixture', setup(builder) {
        builder.onResolve({filter:/\/lib\/modules\.js$/}, () => ({path:'api',namespace:'fixture'}))
        builder.onResolve({filter:/\/lib\/chatTools\.js$/}, () => ({path:'workspace',namespace:'fixture'}))
        builder.onResolve({filter:/\/lib\/labConstants$/}, () => ({path:'constants',namespace:'fixture'}))
        builder.onResolve({filter:/\.\/ContactInbox\.jsx$|\.\/NewsDesk\.jsx$|\.\/CommerceDesk\.jsx$/}, () => ({path:'desks',namespace:'fixture'}))
        builder.onLoad({filter:/.*/,namespace:'fixture'}, ({path:name}) => ({contents:name==='api' ? `
            export const calls=[];
            const modules=[{key:'contact',schema_version:1,dependencies:[],entitled:true,selected:true,installation:{uuid:'contact',schema_version:1,status:'ready',enabled:false,revision:1}}, {key:'news',schema_version:1,dependencies:[],entitled:true,selected:false,installation:null}, {key:'manual_payments',schema_version:2,dependencies:[],entitled:true,selected:true,installation:{uuid:'payments',schema_version:1,status:'ready',enabled:true,revision:3}}];
            let revision=1;
            export const listModules=async()=>structuredClone({revision,modules,business_writes_ready:false});
            export const selectModule=async(_project,key)=>{calls.push(['select',key]); modules.find(m=>m.key===key).selected=true; revision++};
            export const prepareModule=async(_project,{key})=>{calls.push(['prepare',key]); modules.find(m=>m.key===key).installation={uuid:key,status:'prepared',enabled:false,revision:1}; revision++};
            export const proposeModuleSchema=async(_project,uuid)=>{calls.push(['propose',uuid]); return {proposal:{uuid:'proposal',sql:'-- reviewed fixture SQL',impact:{key:uuid,project_ref:'fictional-ref',schema_version:1},status:'pending',impact_hash:'a'.repeat(64)}}};
            export const applyModuleProposal=async()=>{calls.push(['apply']); throw Error('Automatic Apply forbidden')};
            export const setModuleState=async()=>{calls.push(['activate']); throw Error('Automatic activation forbidden')};
            export const cancelModuleProposal=async()=>{}; export const embedModule=async()=>{}; export const moduleEmbedPlan=async()=>{};
        ` : name==='desks' ? 'export default function Desk(){return null}; export const CatalogDesk=Desk, OrdersDesk=Desk, PromptPaySettingsDesk=Desk;' : name==='workspace' ? 'export const readWorkspaceFile=async()=>({full:""})' : 'export const LAB_BTN_OUTLINE="",LAB_BTN_PRIMARY="";' }))
    }
}] })
// Export the fixture call journal from the same bundled module as the component.
const source = await fs.readFile(output,'utf8')
await fs.writeFile(output, source+'\nexport { calls };\n')
const { default: ModulesDesk, calls } = await import(pathToFileURL(output))
let tree
try {
    await act(async()=>{tree=create(React.createElement(ModulesDesk,{projectUuid:'fictional-project'}))})
    const text = node => node.children.filter(child=>typeof child==='string').join('')
    const button = label => tree.root.findAllByType('button').find(node=>text(node).includes(label))
    await act(async()=>{button('ข่าวสารและบทความ').props.onClick()})
    await act(async()=>{await button('เตรียมโมดูลและตรวจ schema').props.onClick()})
    assert.deepEqual(calls,[['select','news'],['prepare','news'],['propose','news']])
    assert.ok(JSON.stringify(tree.toJSON()).includes('SQL'))
    await act(async()=>{await button('ตรวจ schema อีกครั้ง').props.onClick()})
    assert.deepEqual(calls.at(-1),['propose','contact'])
    await act(async()=>{button('บัญชี PromptPay').props.onClick()})
    await act(async()=>{await button('เตรียมโมดูลและตรวจ schema').props.onClick()})
    assert.deepEqual(calls.at(-1),['propose','payments'])
    assert.ok(!calls.some(call=>['apply','activate'].includes(call[0])))
    console.log('PASS: module preparation targets dependencies and old schema upgrades with explicit Apply (5 checks).')
} finally { if(tree) await act(async()=>tree.unmount()); await fs.rm(output,{force:true}) }
