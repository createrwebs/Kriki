# Krikkit Studio UI

The approved visual direction uses a quiet sage frame, a white working sheet and forest-green accents. Sora stays the brand typeface; Noto Sans Thai provides Thai coverage. Owner themes and dark appearance still use the existing theme variables.

The default platform logo is the user-supplied CreateAI image at `public/brand/createai-logo.jpg`, preserved byte-for-byte. Light/dark layouts, Lab, Builder, owner modules and the installer use the full mark at its original proportions. The logo includes its own wordmark, so adjacent duplicate brand names are omitted. Existing administrator-uploaded logos continue to take precedence in site settings.

The public landing page uses the design-taste-frontend `product-performs` direction: left-aligned copy beside a large starter preview with three working choices, followed by examples, workflow, current capabilities and entry actions. Existing editable copy and preview/readiness labels remain authoritative. The interface uses its existing starter artwork; no customer imagery, testimonials or statistics were invented.

Management screens use `framed-sheet`: left navigation, restrained borders, consistent forms and status labels, tabular amounts and clear empty/loading/error states. Owner layout responds to its actual panel width using container queries, including when embedded in Lab; a narrow panel gets horizontal navigation and stacked forms. The shared dashboard shell, Studio home and Builder use the same typography, spacing and control treatment. Generated contact/news/storefront styles remain scoped under `kw-` classes.

Primary files: `resources/css/studio.css`, `resources/css/home/sales.css`, `resources/js/lab/components/modules/`, `resources/js/builder/builder.css` and `resources/js/lab/widget-runtime/widgets.css`. Existing customer source is upgraded through the reviewed widget ownership/CAS mechanism; the refresh does not silently rewrite customer projects or activate modules.

Verification: platform and code-owned generated builds; landing53, source/embed/materialization preservation and nine React/Node suites. App source taste lint has0fail/0warn; landing lint has0fail, with warnings for the deliberately retained brand font and real control/preview boundaries. The font provider returned both requested families. Browser screenshot/contrast/keyboard acceptance at1440/768/390 is still pending because CUA reports browser access disabled; HTTP and static checks are not a substitute. No CLI browser was used to bypass that restriction.
