// Reapply required Laravel cookie/Origin/CSRF corrections after local OpenAPI generation.
import fs from 'node:fs/promises'
import path from 'node:path'
const root='postman/collections/managed-commerce-implemented'
async function walk(dir){
    for(const entry of await fs.readdir(dir,{withFileTypes:true})){
        const file=path.join(dir,entry.name)
        if(entry.isDirectory()){if(entry.name!=='.resources')await walk(file);continue}
        if(!entry.name.endsWith('.request.yaml'))continue
        let value=(await fs.readFile(file,'utf8')).replaceAll('\r\n','\n')
        value=value.replace(/(  - key: Origin\n    value:) [^\n]+/,'$1 "{{publicationOrigin}}"')
        value=value.replace(/(  - key: X-CSRF-TOKEN\n    value:) [^\n]+/,'$1 "{{csrfToken}}"')
        if(file.includes('lab-{project}')){
            value=value.replace(/auth:\n  type: apikey\n  credentials:[\s\S]*?(?=settings:)/,'auth:\n  type: noauth\n')
            if(!value.includes('{{ownerCookieName}}')){
                const cookie=value.includes('headers:\n  - key:')?'  - key: Cookie\n    value: "{{ownerCookieName}}={{ownerSession}}"\n':'  Cookie: "{{ownerCookieName}}={{ownerSession}}"\n'
                value=value.replace('headers:\n','headers:\n'+cookie)
            }
        }else value=value.replaceAll('{{apiKey}}','{{orderToken}}')
        await fs.writeFile(file,value)
    }
}
await walk(root)
const definition=path.join(root,'.resources/definition.yaml')
let value=await fs.readFile(definition,'utf8')
value=value.replace('  baseUrl: /\n  apiKey: ""','  baseUrl: http://localhost:8896\n  publicationOrigin: http://fictional.localhost:8896\n  orderToken: ""\n  ownerCookieName: fictional_module_qa\n  ownerSession: ""\n  csrfToken: ""')
await fs.writeFile(definition,value)
