import{n as e}from"./rolldown-runtime-hePW80VL.js";var t=/\.(png|jpe?g|gif|webp|ico|woff2?|ttf|eot|mp4|webm|pdf|zip)$/i;function n(e=``){return String(e).replace(/\\/g,`/`).replace(/^\.\/+/,``).replace(/^\/+/,``).replace(/\/+/g,`/`).replace(/\/\.$/,``).replace(/\/$/,``)}var r=new Set([`index.html`,`package.json`,`vite.config.js`,`vite.config.ts`,`vite.config.mjs`,`postcss.config.js`,`postcss.config.cjs`,`postcss.config.mjs`,`tailwind.config.js`,`tailwind.config.ts`,`tailwind.config.cjs`,`tailwind.config.mjs`]);function i(e=``){let t=n(e);return t?r.has(t)?!0:t===`src`||t===`public`||t.startsWith(`src/`)||t.startsWith(`public/`):!1}function a(e,t=``){let r=n(e),i=String(t||``).trim();if(!i||i===`.`)return r;let a=i.startsWith(`/`)?n(i):n(r?`${r}/${i}`:i),o=[];for(let e of a.split(`/`))if(!(!e||e===`.`)){if(e===`..`){o.pop();continue}o.push(e)}return o.join(`/`)}function o(e,t){let r=n(e),i=n(t),a=r.includes(`/`)?r.split(`/`).slice(0,-1):[],o=i.split(`/`).filter(Boolean),s=0;for(;s<a.length&&s<o.length&&a[s]===o[s];)s+=1;let c=a.length-s,l=o.slice(s),u=[...Array.from({length:c},()=>`..`),...l].join(`/`);return u?u.startsWith(`.`)?u:`./${u}`:`./`}function s(e){return t.test(n(e))}function c(e=[],t=[]){for(let r of e)if(r){if(r.type===`folder`){c(r.children||[],t);continue}r.type===`file`&&r.path&&t.push(n(r.path))}return t}function l(e={},t=[],{dir:r=`src`,base:i=`untitled`,ext:a=`tsx`}={}){let o=new Set([...Object.keys(e||{}),...c(t)].map(e=>n(e)).filter(Boolean)),s=n(`${r}/${i}.${a}`);if(s&&!o.has(s)&&!f(e,t,s))return s;for(let s=2;s<1e3;s+=1){let c=n(`${r}/${i}-${s}.${a}`);if(c&&!o.has(c)&&!f(e,t,c))return c}return n(`${r}/${i}-${Date.now().toString(36)}.${a}`)}function u(e,t,n=``,r=`.`){let i=a(n,r),o=new Set;for(let t of Object.keys(e||{})){if(i===``){let e=t.split(`/`)[0];e&&o.add(e);continue}if(t===i||!t.startsWith(`${i}/`))continue;let e=t.slice(i.length+1).split(`/`)[0];e&&o.add(e)}return d(t||[],i,o),[...o].sort((e,t)=>e.localeCompare(t))}function d(e,t,r){if(t===``){for(let t of e)t?.name&&r.add(t.name);return}for(let i of e)if(i){if(n(i.path)===t&&i.type===`folder`){for(let e of i.children||[])e?.name&&r.add(e.name);return}i.type===`folder`&&i.children?.length&&d(i.children,t,r)}}function f(e,t,r){let i=n(r);return!i||Object.prototype.hasOwnProperty.call(e||{},i)?!0:p(t||[],i)}function p(e,t){for(let r of e)if(r?.path&&(n(r.path)===t||r.type===`folder`&&p(r.children||[],t)))return!0;return!1}function m(e=[],t,r={}){let i=n(t);return i?h(e,i.split(`/`),``,i,r):e}function h(e,t,n,r,i){let[a,...o]=t,s=e.map(e=>({...e,children:e.children?[...e.children]:e.children})),c=n?`${n}/${a}`:a;if(o.length===0){let e=s.findIndex(e=>e.name===a&&e.type===`file`),t={id:i.id||`file-${r}`,type:`file`,name:a,path:r,kind:i.kind||y(a),sizeLabel:i.sizeLabel||`0 B`,modifiedLabel:i.modifiedLabel||`Just now`,yours:!0};return e>=0?s[e]={...s[e],...t}:s.push(t),v(s)}let l=s.findIndex(e=>e.name===a&&e.type===`folder`);l<0&&(s.push({id:`dir-${c}`,type:`folder`,name:a,path:c,modifiedLabel:`Just now`,children:[]}),l=s.length-1);let u=s[l];return s[l]={...u,children:h(u.children||[],o,c,r,i)},v(s)}function g(e=[],t){let r=n(t);return r?_(e,r):e}function _(e,t){let r=[];for(let i of e){let e=n(i.path);if(e!==t){if(i.type===`folder`){r.push({...i,children:_(i.children||[],t)});continue}e.startsWith(`${t}/`)||r.push(i)}}return r}function v(e){return[...e].sort((e,t)=>e.type===t.type?e.name.localeCompare(t.name):e.type===`folder`?-1:1)}function y(e){let t=String(e).toLowerCase();return t.endsWith(`.md`)||t.endsWith(`.mdx`)?`markdown`:/\.(png|jpe?g|gif|webp|svg)$/.test(t)?`image`:`code`}function b(e){try{let t=JSON.parse(e||`{}`);return t.dependencies&&typeof t.dependencies==`object`?{...t.dependencies}:{}}catch{return{}}}var x=`src/components/ui/button.jsx`;function S(e={}){for(let[t,n]of Object.entries(e))if(/\.(jsx|tsx)$/i.test(String(t||``))&&/<Button\b[^>]*\basChild\b/.test(String(n||``)))return!0;return!1}function C(e=``){let t=String(e||``);return!t.includes(`Button`)||t.includes(`asChild`)?!1:t.includes(`...props`)||t.includes(`export function Button`)}function w(e={},t=``){if(!e||typeof e!=`object`)return{files:e||{},changed:!1};if(!S(e)||!t)return{files:e,changed:!1};let r=Object.keys(e).find(e=>n(e)===x);return r?C(e[r])?{files:{...e,[r]:t},changed:!0}:{files:e,changed:!1}:{files:{...e,[x]:t},changed:!0}}var T=e({CANONICAL_BUTTON:()=>E,buttonNeedsAsChildPatch:()=>C,normalizeGuestUiKit:()=>D,projectUsesButtonAsChild:()=>S}),E=`import { Children, cloneElement, isValidElement } from 'react'
import { cva } from 'class-variance-authority'
import { cn } from '../../lib/utils'

const buttonVariants = cva(
  'inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium transition duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/40 disabled:pointer-events-none disabled:opacity-50',
  {
    variants: {
      variant: {
        primary: 'bg-fg text-canvas hover:opacity-90',
        accent: 'bg-accent text-white hover:opacity-90',
        secondary: 'border border-line bg-surface text-fg hover:bg-soft',
        outline: 'border border-line text-fg hover:border-fg/30 hover:bg-soft',
        ghost: 'text-fg-muted hover:bg-soft hover:text-fg',
      },
      size: {
        sm: 'h-8 px-3',
        md: 'h-9 px-4',
        lg: 'h-11 px-6 text-[15px]',
      },
    },
    defaultVariants: {
      variant: 'primary',
      size: 'md',
    },
  },
)

/**
 * Minimal shadcn-style button primitive for Lab scaffolds.
 * Supports \`asChild\` so \`<Button asChild><Link … /></Button>\` merges classes
 * onto the child instead of nesting a button inside a link.
 */
export function Button({
  className,
  variant,
  size,
  type = 'button',
  asChild = false,
  children,
  ...props
}) {
  const classes = cn(buttonVariants({ variant, size }), className)

  if (asChild) {
    const child = Children.only(children)
    if (! isValidElement(child)) {
      throw new Error('Button with asChild expects a single React element child.')
    }
    return cloneElement(child, {
      ...props,
      className: cn(classes, child.props.className),
    })
  }

  return (
    <button type={type} className={classes} {...props}>
      {children}
    </button>
  )
}

export { buttonVariants }

export default Button
`;function D(e={},t=E){return w(e,t)}export{c as a,u as c,b as d,f,m as g,a as h,S as i,l,g as m,D as n,s as o,o as p,T as r,i as s,E as t,n as u};