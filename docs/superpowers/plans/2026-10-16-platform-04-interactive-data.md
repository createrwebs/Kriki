# Interactive Data Applications Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** AI can propose entities/fields and generate working forms, lists and detail pages whose allowed actions persist real records safely.

**Architecture:** Versioned entity schemas and project access policy consumed by managed API/React SDK; backendvalidation and approvedadditiveDDL. Keep managedbooking/commerce domains separate fromcustomentities.

**Tech Stack:** Laravel13, React19, pinned Supabase, existingproposalrendererpatterns.

**Spec:** ../specs/2026-10-16-platform-evolution-design.md §6–7

**Status:** Draft. Dependency:S2–S4; commercecanremainindependent. Gate D.

## Global Constraints

- การแก้ schema ต้องแสดง diff และให้เจ้าของ Apply แบบ server-issued approval; ห้าม AI boolean เป็นการอนุมัติ
- Managed module mutations ใช้ schema validation, optimistic version, idempotency และ atomic database operation
- ไม่ drop/reset ข้อมูลเดิมหรือเปลี่ยนโครงการ Supabase อัตโนมัติ; migrations additive และ rollback แบบไม่ทำลายข้อมูล
- Spec§4 applies;20entities/project,50activefields/entity,100rows/page,16KiBrecordbody. NoarbitrarySQL/tablename frompubliccaller.

## Review Focus

1. Thai/duplicatelabel rename remapsanswers — D1 stableIDtests.
2. Newrequiredfield onexistingrows losesdata — D1/D2 compatibilitytests.
3. Promptasksadminlistwithnoguestidentity — D3 denybydefaulttests.
4. Twoidentities orprojects sharecache/readfilters — D2 scopefiltertests.
5. Retry/createoroptimisticedit duplicates/overwrites — D2 idempotency/CAS.

## Task D1: Entity schema proposals and migration planner

**Files:** Create app/Lab/Data/EntityDefinition.php, EntitySchemaPlanner.php, EntityRegistry.php; database/sql/lab-data/0001_registry.sql; resources/js/lab/lib/entityDefinition.js; tests/fixtures/data/entity-v1.json; tests/regressions/entity-definition.php, entity-definition.test.mjs. ReuseS3proposalstore ratherthanparallelapprovalsystem.

**Interfaces:** EntityDefinition::validate(array $definition,?array $previous):array; EntitySchemaPlanner::propose(User,LabProject,array $operations,int $version):ChangeProposal. Types text/textarea/integer/decimal/boolean/date/datetime/select/reference; entity/fieldIDs stableUUID; typeparameters decimalprecision18/scale2 initial; label1–120/plaintext.

- [ ] Red: duplicatelabelsThai preserveIDs; archivepreserveshistoricalanswers;51fields/21entitiesfail; foreignprojectreferencefail; maliciousidentifier ignored/rejected; incompatibletype/requiredexistingnulls givesimpactblock not silentDDL.
- [ ] Implement schemaJSONsamefixturePHP/JS, projectnamespaceownedregistry, allowlistedadditiveDDLwithcompatibilityquery; separateapply usesS3immutableapprovalhash. v1requiresnullablefornewfields onpopulatedentity unlessapproveddefaultbackfill runsandverifiesbeforeconstraint; no destructive conversion/drop.
- [ ] Green: PHP/Nodefixtures, fakeDB anduniqueisolatedSupabasemigration rollback; proposalonlydoesnotmutate live schema. RecordactualSQL/versionmappings server-side, nevergeneratedprojectsource.
- [ ] Docs entitycontract+examples, independentreview beforeApplyenabled.

## Task D2: Scoped record API and validation

**Files:** Create app/Lab/Data/RecordService.php, RecordValidator.php, EntityAccess.php; app/Http/Controllers/Lab/LabEntityController.php, app/Http/Controllers/Api/LabEntityPublicController.php; routes/lab-data.php; docs/api/data.openapi.yaml; tests/regressions/entity-records.php.

**Interfaces:** RecordService::create(context,entityUuid,array $values,string $key,int $schemaVersion):array; update(context,entityUuid,recordUuid,array $values,int $expectedRecordVersion,int $schemaVersion):array; list(context,entityUuid,filters,cursor):array. Typedcontextresolvedfromownerorverifiedsubject; not bodyowner_id. Responses `{record_uuid,record_version,schema_version,values}`; page25default100max, whitelistedfilterops eq/contains/range/sort onownedfields.

- [ ] Red: unknownfields422; schema409preservesinput; exactkeyreplayoneinsert; samekeydifferentpayload409; staleedit409; referencecrossproject404; filtercannotinjectSQL/PostgREST; twoaccounts/projectscaches don'tmix.
- [ ] Implementpreparedidentifier mapping/servervalidation fromdefinition. OwnerCRUD usesauth+CSRF; anonymouspublicformonlyallowlistedcreatefields/statusservercontrolled; noanonymouslist/update/delete. Softarchiveownerrecordswithaudit; noharddeleteendpointfirstrelease.
- [ ] Green: realHTTPpipeline/error/method/contenttype/ratechecks; rowlevelpolicy2users+owner; upstreamcredentialneverexposed; list/redactederrorsnodataoracle. IntegrationtestSQLite/fakeHTTPdoesnotestablishliveRLS; opt-inrealRLSfixture separately.
- [ ] PublishlocalOpenAPIcontract/negativeexamples noPostmanremotepush; recordscopedquotas/rowlimits.

## Task D3: Optional end-customer identity boundary

**Files:** Create app/Lab/Identity/ProjectIdentity.php, ProjectJwtVerifier.php, SubjectAccess.php; tests/regressions/project-identity.php; config/lab-identity.php; generatedruntimeidentityclient onlyifcustomerlogin requested.

**Interfaces:** ProjectIdentity::resolve(LabProject,string $jwt):SubjectContext; verifiespinnedissuer/audience/signature/expiry, cachesJWKS scopedissuer/keyID, noacceptunverifiedclaims. SubjectAccess mapsverifiedsub+project topublicform/selfrecordpermissions; signupemaildoesnotmakeadmin.

- [ ] Red: tokenfromanotherSupabase/ref/project,wrongissuer/aud/alg/expired/missingkeyfailclosed; duplicateemailcannotreadanotheruserrecord; tokenrefreshdoesnotcrossproject. Platformownerloginnotforwarded.
- [ ] Implementidentityonlyneededforcustomerread/update; keepanonymousformsusableandownerCRUDindependent. Explicitrolepolicy server-owned; no client role=admin. Ifidentityinfranotconfigured returnsetuprequired, notpublicreadfallback.
- [ ] GreenJWTfixture+realprovider opt-in withfictionalaccounts; keyrotation/outage behavior known. RLSclaimrequirementsdocumentedandtested; noallow-allpolicies.
- [ ] Gate: no self-servicecustomerCRUD untilD3passes; thisdoesnotblockowneradminandanonymouscreateonly.

## Task D4: Generated SDK, data desk and typed AI actions

**Files:** Create resources/js/lab/data-runtime/client.js, DataForm.jsx, RecordsTable.jsx, RecordDetail.jsx; components/data/EntityDesk.jsx, EntityProposalCard.jsx; app/Lab/Data/DataEmbed.php; tests/regressions/data-runtime.test.mjs, entity-ai-proposals.php. Modify LabToolCatalog/StageContextContract, native+pseudoallowlists, toolRuntime/runTurn/useLabChat, WorkspaceTabs/Stage.

**Interfaces:** Tools `read_entity({entity_uuid})`, `propose_entity_change({entity_uuid?,expected_version,operations})`, `read_module_manifest({})`; noapply/recordexporttool. SDK usesentityUUID/schemaVersion, notrawtable. Componentstates idle/loading/empty/validationerror/conflict/saving/success.

- [ ] Red: Thai `เพิ่มเลขทะเบียนรถ` produces proposalnotmigration; ownerApply addsstablefield; formrendernewfield; nofilesdirectlyeditingmanagedschema; noforcingwrite_file aftersuccessfulproposal. AIobservationscontaindefinition, notcustomerrecords.
- [ ] Implementcommoncontractrenderers/transport, field/servererrormapping, version409keepsanswers, pendingbackendstateexplicit. ProtectedSDKimmutablethroughnormalAIfiletools; visualwrappereditable. Intentparserselectswebsiteonly vsappwithdata andsurveybeforeproposal.
- [ ] Green: actualbrowsercreate/edit/search/refetch/refreshpersistentrecords; two-projecttests; buildactualgeneratedReactartifactawaitguard; loading/error/paginationkeyboardstates. Read_onlydemo is labeledandnotusedasproofofpersistence.
- [ ] Agentreportmustincludeoneactualrecordreferenceandsanitizedownerproof, entity/schema versions, failedcasesandconstraints. GateD followsreviewandfeature-readinessupdate.

## Execution Handoff

Do not builda universal ORM/querylanguage orReflexengineinthisphase. Keep customCRUDawayfrommanagedorders/bookings toprotectfinancialinvariants. Readmasterhandoffandrespectsharedbackendnamespaceapprovalgates.
