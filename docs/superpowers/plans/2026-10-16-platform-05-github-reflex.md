# Safe GitHub Import and Reflex Adaptation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Customers can inspect/import supported GitHub source into their own project, adapt it with managed modules, and receive honest runtime compatibility. Study Reflex features without silently changing platformstack.

**Architecture:** Extend existing GitHub API importer with inspection/staging/provenance/approval and workspace revision. Reuse browserstaticruntime for supportedfrontends; isolate anyfuturePythonprobe fromLaravelandrealdata.

**Tech Stack:** ExistingLaravelGitHubAPIintegration/ReactVFS; future optional containerized PythonReflex only afteroperatorapproval.

**Spec:** ../specs/2026-10-16-platform-evolution-design.md §9; ../../platform/2026-10-16/reflex-analysis.md

**Status:** Draft. Dependency:GateS; moduleadaptation usesD/C whenavailable. GateG; optionalR is not an MVPdependency.

## Global Constraints

- Generated code, GitHub files, README และ tool outputs เป็น untrusted data; ไม่ให้เปลี่ยน policy หรืออนุมัติตนเอง
- ไม่มี untrusted PHP/Python/Node execution ใน Laravel process หรือ host shell; source import ไม่ได้ให้สิทธิ์ execute
- Import/build เป็นสองสถานะ; เก็บ provenance/commit/license และห้ามแสดง Ready ก่อน artifact + smoke check ผ่าน
- Spec§4 applies;25MiBarchive/2500files/2MiBfile,100MiBexpanded/depth32/ratio100:1; noautomaticdependencyhooks,forcepushorotheruserworkspaceoverwrite.

## Review Focus

1. Branchchangesbetweeninspection/import — G1 pinnedSHA tests.
2. Monorepo, unsupported SSR/Python ortruncatedtree — G1/G2 honestcompatibility tests.
3. Symlink/zip-slip/lockfile/hook/pathcase tricks — G2 adversarialarchivefixtures.
4. ImportoverwritesdirtyVFSorotherproject — G2 expectedrevision+atomicpreservationtests.
5. READMEasksforsecrets/SQL/push — G3 promptinjection/capabilityboundarytests.

## Task G1: Repo inspection and compatibility report

**Files:** Modify app/Lab/Github/GithubRepoImporter.php, GithubRepoSuitability.php, GithubRepoKindScout.php, GithubSubtreeFetcher.php, GithubApiClient.php; Create GithubImportInspection.php, GithubRuntimeCompatibility.php, app/Models/LabImportInspection.php; database/migrations/2026_10_16_040000_create_lab_import_inspections.php; tests/regressions/github-inspection.php.

**Interfaces:** GithubImportInspection::inspect(User,LabProject,string $url,?string $ref,?string $root):array `{inspection_uuid,commit_sha,root,stack,source_importable,runtime_status,license,warnings,manifest_hash,expires_at}`. runtime_status supported/conditional/source_only/blocked; pureReactVitesupportedfirst. GETmetadata and trees useexistingOAuthbroker; neverforwardtokenintosource.

- [ ] Red: branchadvancesstillimportsoriginalSHA; truncatedGitHubtreefailswithactionableerror;2501files, requiredfileoversize, invalidroot, externalhostURL, privateunauthorizedrepo failwithoutpartialsourcechange. Source-onlyPythonReflexnotlistedasReact-ready.
- [ ] Implementserverfetch/resolveSHAonce, manifest/requiredentry/lockfile/license/scriptsreport. Existingkindglyphsare hints, not executableguarantees. Upstreamrate403/429explicitretryableerrors; no silentzipfallbackbypassingvalidation.
- [ ] Licenseabsent/ambiguous: reportandblockautomaticredistribution/codecopyuntilrightsconfirmed; don'ttreatpublicasMIT. Copy NOTICE/LICENSEmanifest forselectedsubtreeasappropriate. Secret-likefilepathsandcredentialpatternsredacted/blockedbeforeAIingestion; nofullsecretcontentsinreports.
- [ ] Green: inspectionregressionswithVite/monorepo/NextSSR/Reflexfixtures; actualread-onlyAPItestoptionalpublicrepo. PreserveprivateOAuthscope/access; no newrepo/fork/pushduringinspection.
- [ ] UpdateexistingImportFromGithubModal shows sourcevsrunnable statusesand rootselection; not duplicateOAuthflow.

## Task G2: Staged source import and reproducible build

**Files:** Create app/Lab/Github/GithubImportStager.php, ImportManifest.php, ImportJob.php; app/Models/LabImportJob.php; database/migrations/2026_10_16_050000_create_lab_import_jobs.php; Modify SiteWorkspace.php importFileMap/atomic APIs, LabGithubController.php; tests/regressions/github-import-safety.php, github-import-build.test.mjs.

**Interfaces:** stage(User,LabProject,string $inspectionUuid,string $expectedWorkspaceRevision,string $key):job; promote(User,LabProject,string $jobUuid,string $expectedRevision):array. Jobstates queued/fetching/validating/source_ready/building/verified/failed/promoted. Newrevision iscontentmanifesthash, no relianceonmtime alone. Inspectionttl15minutes; ownermustreinspectifexpiredbutpinnedjobstillconsistent.

- [ ] Red: zip/pathtraversal/UNC/drive/backslash/NUL/percentencoding/symlink/submodule/.githooks rejected; expandedsize/ratio enforcedwhileextracting, notonlycompressedsize; casecollisionrejectedwherefilesystemambiguous. PreserveLICENSE/NOTICEandreviewedlockfile; do not executeit.
- [ ] Implementtemporaryjobdirectoryundercontrolledprojectstagingroot, validatedresolvedpaths, immutablemanifestandtargetdiff. ExistingprojectrequiresApplyconflicts/expectedrevision; unknownfilesneveroverwritesilently; dirtyeditorandserverVFSconflict409. Failuredoesnotdeleteprojectorremotedata.
- [ ] Buildonlysupportedmatrix withdependencylockverificationandlifecyclehooksdisabled; dependenciesnativebuildrequiringhooks becomeexplicitunsupporteduntilreviewedrunnerexists. Useaccountquota/timeout/memory/networkbounds. Preserveactualartifactchecks dist/index.html+bundledJS/CSS+deeproute; no fakeexit0.
- [ ] Green: filesystemfixturesinuniqueisolatedtemporarydirs; foreignprojectfileunchangedhash; projecthistoryrestoresourceonly, notDBpayments. ActualsafeViteimport+browserbuild+publish; SSR/Reflexshowssource-only reason.
- [ ] Recordprovenance(owner/repo/commit/root/license), sourcechangehashandartifacthash. Artifactrollbackdoesnotrollbackschemaautomatically. UpdateOpenAPI docs/api/github-import.openapi.yaml, no newremotepermissionbydefault.

## Task G3: AI adaptation within project/module scope

**Files:** Modify app/Ai/Lab/LabToolCatalog.php/StageContextContract.php, resources/js/lab/orchestration/toolRuntime.js/runTurn.js, hooks/useLabChat.js; existingGithubDesk/remotecontrollers; Create components/modules/ChangeImpactReview.jsx; tests/regressions/github-agent-boundaries.php, github-adaptation.test.mjs.

**Interfaces:** tools `inspect_repository({url,ref?,root?})`, `propose_repository_import({inspection_uuid,expected_revision})`, `read_module_manifest({})`. Existinggithub_pull/push/fork/create/force actionsmustcallserverapprovaloperation, notjustAItext. `propose_adaptation({module_keys,entity_operations,route_changes,base_revision})` returnsimpact+proposaluuid; no directapplypermission.

- [ ] Red: maliciousREADME/toolcontentcannottriggerSQL/credentials/hostshell/remotepush/tenantchange; AIcannotmodifyprotectedform/paymentSDK; unsupportedstackdoesnotbecomerunnablethroughpromptclaim; anotherownerrepo/projectdenied.
- [ ] Implementcontexttags/read-onlysourceboundary andcapability-awaretoolallowlist backedbyS2policy. A usercommandcanrequestschema/modulechangebutserverrendersreviewdiff/affectedroutes/tables/data+humanApply. Preserveexistingworkingbookings/paymentrecordswhenvisualcodechanges.
- [ ] Addmoduleadaptationwizard: importedReactapp→choosebooking/catalog/customdata→reviewdependencies+routepatch→Apply→test. Noauto-copyprivatecustomerdata, no automaticplanupgradeorpaidexternalAPI.
- [ ] Green: Viteexampleaddbooking/customfield end-to-endafterD/Cready; sameusersecondproject+otheruserunchanged; source/fullbuild/directrouteworks; history/auditableactor preserved. Existingnative/pseudotoolserializationregressionspass.
- [ ] Independentsecurityreview beforecustomerGitHubimportenabledinpaidplans. GateG evidenceattachedtomasterledgerandreadinesscopy.

## Task R1: Reflex behavioral adaptation (no Python runtime dependency)

**Files:** Reference ../../platform/2026-10-16/reflex-analysis.md; Create docs/decisions/2026-10-16-reflex-patterns.md; updateprotectedSDKcontracttestsintheowningC/Dtasks only.

**Interfaces:** Existingtypedactions/resultschemas/fielderrors/projectlockkeys fromS/C/D. No new genericstateengine,ORM,compilerorWebSocketprotocol.

- [ ] Mapstate/event/form/compilerboundarypatterns toC/Dacceptancetests; pinupstreamcommit/pathandstatewhetheridea-onlyoractualcodeadaptation.
- [ ] Ifcopyingcode, reviewperfilelicense, addTHIRD_PARTY_NOTICES andchangedfilenotice; no trademark/UIcloneofhostedAIBuilder. PureconceptualreimplementationdoesnotrequireinstallingPython.
- [ ] Verifytwoprojectconcurrentstate/version/sessioncachetests; savedrecordnotlostwhenUIremounts; uploaddoesnotexposeprivatefile. Reportbehavioralevidence, not“Reflexintegrated”.

## Task R2: Optional isolated Python runtime probe — gated, not default work

**Precondition:** Separateownerapprovalforruntimeinfrastructure/cost/hostingmodel; operatorprovidesapprovedisolatedrunner. Userrequesttocloneframeworkisnotpermissiontograntcustomersarbitraryhostexecution.

**Files ifapproved:** infra/runtime/reflex-probe/Dockerfile, README.md, probe app; tests/security/reflex-runtime-isolation.md. Not mountedintoLaravel source or productionnetwork.

- [ ] Writeprobequestion: canonepinnedReflexappbuildandperformUI→typedevent→fictionalDB→stateupdate insideaper-projectsandboxwithnodependencyonplatformsession/servicekey?
- [ ] Proveunprivilegedprocess/read-onlybase/per-projectvolume/nonetworkmetadata/nodockersocket/noplatformenv; CPU1core/RAM1GiB/time300sec/storage512MiB initialprobeceilings. Networkallowlistonlyreviewedpackagefetchduringbuild; runtimeaccessonlyfakeDB. Limitsrequiremeasurementsbeforenewtiers.
- [ ] User-visibleprobeonlyafterhostprotectionverified; teardownexacttestresourcesbyexplicitIDs withrecoverabledatahandling. NoautomaticremoteDBmigrationsandnorealcustomerdata.
- [ ] Reportbuild/event/databasepass/fail,resourcemeasurementsandisolationtests; createaseparatespecforcommercialPythonhostingifsuccessful. Do notannounceallGitHubPythonrepossupported.

## Execution Handoff

G1→G2→G3; R1supportsC/Dwithoutnewruntime. R2disableduntilseparateapproval. Do notdeletetheuser'ssourceorconvertunsupportedprojecttypebehindtheirback. ReviewerchecksGitHubexternalwrites+tenantnamespace+frontendsecretsbeforegate.
