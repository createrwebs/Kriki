import { useEffect, useRef, useState } from "react";
import {
  createBlueprint,
  editBlueprint,
  isUuid,
  prepareSave,
  readDraftList,
  sameBlueprint,
  validateBlueprint,
} from "./blueprint.js";
import { builderRequest } from "./transport.js";
import { BlueprintPreview } from "./BlueprintPreview.jsx";

function Field({
  label,
  value,
  onChange,
  multiline = false,
  limit,
  disabled = false,
}) {
  const Tag = multiline ? "textarea" : "input";
  return (
    <label className="builder-field">
      <span>{label}</span>
      <Tag
        value={value}
        onChange={(e) => onChange(e.target.value)}
        disabled={disabled}
        rows={multiline ? 3 : undefined}
        aria-invalid={Array.from(value).length > limit || undefined}
      />
      <small className="builder-hint">
        {Array.from(value).length} / {limit} characters
      </small>
    </label>
  );
}

function fieldLabel(path, blueprint) {
  const labels = {
    blueprint: "Draft size",
    title: "Website title",
    summary: "Website brief",
    kind: "Website kind",
    template_id: "Template",
    schema_version: "Design version",
    start_mode: "Starting method",
    pages: "Page structure",
    "shared.brand": "Brand name",
    "shared.footer": "Footer text",
    "theme.palette": "Palette",
    "theme.font": "Typography",
    "theme.radius": "Corners",
  };
  if (Object.hasOwn(labels, path)) return labels[path];
  const match = path.match(
    /^pages\.(\d+)(?:\.sections\.(\d+)(?:\.items\.(\d+))?)?(?:\.(\w+))?$/,
  );
  if (!match) return "Draft details";
  const [, p, s, i, key] = match;
  const page = blueprint?.pages[Number(p)];
  const location = [
    page?.title || `Page ${Number(p) + 1}`,
    s !== undefined ? `section ${Number(s) + 1}` : null,
    i !== undefined ? `item ${Number(i) + 1}` : null,
  ]
    .filter(Boolean)
    .join(", ");
  return `${location}: ${{ route: "page path", navigation: "navigation visibility", title: "title", heading: "heading", body: "description", variant: "layout", type: "section type", id: "identity", items: "items", sections: "sections" }[key] || "details"}`;
}

export function BuilderApp({ bootstrap }) {
  const { catalog, storage_ready: storageReady } = bootstrap;
  // Fixed same-origin paths: response data and query parameters never choose a URL.
  const endpoint = "/lab/builder/drafts";
  const [blueprint, setBlueprint] = useState(null);
  const [draft, setDraft] = useState({ uuid: null, revision: null });
  const [saved, setSaved] = useState(null);
  const [history, setHistory] = useState([]);
  const [kind, setKind] = useState("business");
  const [templateId, setTemplateId] = useState("business-studio");
  const [title, setTitle] = useState("My new website");
  const [summary, setSummary] = useState("");
  const [pageId, setPageId] = useState(null);
  const [overview, setOverview] = useState(true);
  const [drafts, setDrafts] = useState([]);
  const [busy, setBusy] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [pendingCreate, setPendingCreate] = useState(null);
  const [notice, setNotice] = useState("");
  const [creatingProject, setCreatingProject] = useState(false);
  const [createdProject, setCreatedProject] = useState(null);
  const busyRef = useRef(false);
  const [aiPrompt, setAiPrompt] = useState("");
  const [aiScope, setAiScope] = useState("all");
  const [aiTargetSection, setAiTargetSection] = useState(null);
  const [aiLoading, setAiLoading] = useState(false);
  const [uploadingImage, setUploadingImage] = useState(false);
  const uploadTargetRef = useRef(null);
  const fileInputRef = useRef(null);
  const [projectsList, setProjectsList] = useState([]);
  const [loadingProjects, setLoadingProjects] = useState(false);
  const dirty = Boolean(
    blueprint &&
      (saved === null || !sameBlueprint(blueprint, JSON.parse(saved))),
  );
  const locked = busy || loading || Boolean(pendingCreate) || aiLoading || uploadingImage;
  const validation = blueprint ? validateBlueprint(blueprint, catalog) : {};
  const page =
    blueprint?.pages.find((p) => p.id === pageId) ?? blueprint?.pages[0];
  const compatible = catalog.templates.filter((t) => t.kinds.includes(kind));

  const refreshDrafts = async () => {
    if (!storageReady) return;
    const result = await builderRequest(endpoint);
    setDrafts(readDraftList(result, catalog));
  };
  const acceptDraft = (
    result,
    { loaded = true, uuid, revision, blueprint: submitted } = {},
  ) => {
    if (
      !result ||
      !isUuid(result.uuid) ||
      !Number.isInteger(result.revision) ||
      result.revision < 1 ||
      Object.keys(validateBlueprint(result.blueprint, catalog)).length
    )
      throw new Error(
        "The saved draft has an unsupported format. Your current edits are still here.",
      );
    if (
      (uuid !== undefined &&
        result.uuid.toLowerCase() !== uuid.toLowerCase()) ||
      (revision !== undefined && result.revision !== revision) ||
      (submitted !== undefined && !sameBlueprint(result.blueprint, submitted))
    )
      throw new Error(
        "The saved draft could not be confirmed for this request. Your edits are still here. The server may have a different saved revision.",
      );
    setBlueprint(result.blueprint);
    setDraft({ uuid: result.uuid, revision: result.revision });
    setSaved(JSON.stringify(result.blueprint));
    setPendingCreate(null);
    if (loaded) {
      setCreatedProject(null);
      setHistory([]);
      setPageId(result.blueprint.pages[0].id);
    }
    window.history.replaceState(null, "", `/lab/builder?draft=${result.uuid}`);
  };
  const loadDraft = async (uuid, initial = false) => {
    if (busyRef.current || !isUuid(uuid)) return;
    if (
      !initial &&
      dirty &&
      !window.confirm("Discard your unsaved edits and load this saved draft?")
    )
      return;
    busyRef.current = true;
    setLoading(true);
    setError(null);
    setNotice("");
    try {
      const result = await builderRequest(`${endpoint}/${uuid}`);
      acceptDraft(result?.draft, { uuid });
    } catch (e) {
      setError(e);
    } finally {
      busyRef.current = false;
      setLoading(false);
    }
  };
  useEffect(() => {
    let active = true;
    if (storageReady)
      builderRequest(endpoint)
        .then((result) => {
          const list = readDraftList(result, catalog);
          if (active) setDrafts(list);
        })
        .catch((e) => {
          if (active) setError(e);
        });
    const params = new URLSearchParams(window.location.search);
    const query = params.get("draft");
    const projectQuery = params.get("project");
    if (projectQuery && isUuid(projectQuery) && storageReady) {
      fetch(`/lab/builder/project-draft/${encodeURIComponent(projectQuery)}`, {
        credentials: "same-origin",
        headers: { Accept: "application/json" },
      })
        .then((r) => r.json())
        .then((data) => {
          if (active && data?.draft_uuid) {
            loadDraft(data.draft_uuid, true);
            setCreatedProject({ uuid: projectQuery, revision: data.draft_revision });
          }
        })
        .catch(() => {});
    } else if (query && !isUuid(query))
      setError(
        new Error("The draft link is invalid. Choose a saved draft below."),
      );
    else if (query && storageReady) loadDraft(query, true);
    else if (!query && (params.get("from") === "studio" || params.get("template") || params.get("seed"))) {
      try {
        const seedParam = params.get("seed");
        const seedKey = seedParam
          ? `krikkit.studio.seed.${String(seedParam).toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-+|-+$/g, "") || "default"}`
          : null;
        const raw =
          (seedKey && window.localStorage?.getItem(seedKey)) ||
          window.localStorage?.getItem("krikkit.builder.studio-handoff") ||
          window.sessionStorage?.getItem("krikkit.builder.studio-handoff");
        if (raw) {
          const parsed = JSON.parse(raw);
          const candidate = parsed?.blueprint;
          if (candidate && Array.isArray(candidate.pages) && candidate.pages.length > 0) {
            // Ensure section variants match catalog even if saved from an older localStorage entry
            const repaired = structuredClone(candidate);
            repaired.pages.forEach((p) => {
              if (!Array.isArray(p.sections)) return;
              p.sections.forEach((s) => {
                const st = catalog.section_types.find((t) => t.id === s.type);
                if (st && !st.variants.includes(s.variant)) {
                  s.variant = st.variants[0] || "default";
                }
              });
            });
            if (Object.keys(validateBlueprint(repaired, catalog)).length === 0) {
              setBlueprint(repaired);
              setPageId(repaired.pages[0].id);
              setKind(repaired.kind);
              setTemplateId(repaired.template_id);
              setTitle(repaired.title);
              setSummary(repaired.summary);
            }
          }
        }
      } catch {
        // Ignore invalid handoff state
      }
    }
    return () => {
      active = false;
    };
  }, []);
  useEffect(() => {
    if (!dirty) return;
    const guard = (e) => {
      e.preventDefault();
      e.returnValue = "";
    };
    window.addEventListener("beforeunload", guard);
    return () => window.removeEventListener("beforeunload", guard);
  }, [dirty]);

  const edit = (operation) => {
    if (locked || busyRef.current) return;
    try {
      const next = editBlueprint(blueprint, operation);
      setHistory((h) => [...h.slice(-39), blueprint]);
      setBlueprint(next);
      setError(null);
    } catch (e) {
      setError(e);
    }
  };
  const newDraft = () => {
    if (busyRef.current) return;
    if (
      dirty &&
      !window.confirm(
        "Discard your unsaved edits and start a new design draft?",
      )
    )
      return;
    setBlueprint(null);
    setCreatedProject(null);
    setDraft({ uuid: null, revision: null });
    setSaved(null);
    setHistory([]);
    setPendingCreate(null);
    setError(null);
    setNotice("");
    window.history.replaceState(null, "", "/lab/builder");
  };
  const start = () => {
    try {
      const next = createBlueprint(catalog, {
        kind,
        templateId,
        title,
        summary,
      });
      const errors = validateBlueprint(next, catalog);
      if (Object.keys(errors).length) {
        setError({ message: "Check the highlighted draft details.", errors });
        return;
      }
      setBlueprint(next);
      setPageId(next.pages[0].id);
      setSaved(null);
      setHistory([]);
      setError(null);
      setNotice("");
    } catch (e) {
      setError(e);
    }
  };
  const save = async () => {
    if (
      busyRef.current ||
      !storageReady ||
      !blueprint ||
      Object.keys(validation).length
    )
      return;
    const snapshot = prepareSave(blueprint, draft, pendingCreate);
    busyRef.current = true;
    setBusy(true);
    setError(null);
    setNotice("");
    if (!draft.uuid) setPendingCreate(snapshot);
    try {
      const result = await builderRequest(
        draft.uuid ? `${endpoint}/${draft.uuid}` : endpoint,
        {
          method: draft.uuid ? "PUT" : "POST",
          body: draft.uuid
            ? {
                expected_revision: snapshot.revision,
                blueprint: snapshot.blueprint,
              }
            : { request_id: snapshot.requestId, blueprint: snapshot.blueprint },
        },
      );
      acceptDraft(result?.draft, {
        loaded: false,
        uuid: snapshot.uuid ?? undefined,
        revision: snapshot.uuid ? snapshot.revision + 1 : 1,
        blueprint: snapshot.blueprint,
      });
      refreshDrafts().catch(() =>
        setNotice(
          "Your draft was saved, but the saved draft list could not refresh. Reload this page to refresh the list.",
        ),
      );
    } catch (e) {
      // A validation/authorization rejection is definitive. Network/5xx and
      // unreadable successes remain uncertain and retain the creation key.
      if (!draft.uuid && e.status >= 400 && e.status < 500)
        setPendingCreate(null);
      setError(e);
    } finally {
      busyRef.current = false;
      setBusy(false);
    }
  };
  const undo = () => {
    if (locked || busyRef.current || !history.length) return;
    setBlueprint(history.at(-1));
    setHistory((h) => h.slice(0, -1));
    setError(null);
  };
  const createProject = async () => {
    if (busyRef.current || locked || dirty || !draft.uuid) return;
    const snapshot = { ...draft };
    busyRef.current = true;
    setBusy(true);
    setCreatingProject(true);
    setError(null);
    try {
      const result = await builderRequest(`${endpoint}/${snapshot.uuid}/project`, {
        method: "POST", body: { expected_revision: snapshot.revision },
      });
      if (!isUuid(result?.project?.uuid) || result?.source?.draft_uuid !== snapshot.uuid
        || result.source.draft_revision !== snapshot.revision) {
        throw new Error("The website project response could not be confirmed. Retry to recover the same project.");
      }
      setCreatedProject({ uuid: result.project.uuid, revision: snapshot.revision });
    } catch (e) {
      setError(e);
    } finally {
      busyRef.current = false;
      setBusy(false);
      setCreatingProject(false);
    }
  };
  const selectPage = (id) => {
    setPageId(id);
    setOverview(false);
  };
  const runAiEdit = async (customPrompt = null, customScope = null, customSectionId = null) => {
    if (busyRef.current || locked || !blueprint) return;
    const text = String(customPrompt ?? aiPrompt ?? blueprint.summary ?? "").trim();
    if (!text) {
      setError({ message: "กรุณาพิมพ์คำสั่ง Prompt หรือกรอก Website brief เพื่อให้ AI ช่วยแก้ไขหน้าเว็บ" });
      return;
    }
    const targetScope = customScope || aiScope || "all";
    const targetSecId = customSectionId ?? (targetScope === "section" ? aiTargetSection?.id : null);
    busyRef.current = true;
    setAiLoading(true);
    setError(null);
    setNotice("กำลังส่งคำสั่งให้ AI ปรับปรุงโครงสร้างและเนื้อหาหน้าเว็บ…");
    try {
      const result = await builderRequest("/lab/builder/ai-edit", {
        method: "POST",
        body: {
          prompt: text.slice(0, 2000),
          scope: targetScope,
          page_id: page?.id || blueprint.pages[0]?.id,
          section_id: targetSecId,
          blueprint,
        },
      });
      if (!result?.blueprint || Object.keys(validateBlueprint(result.blueprint, catalog)).length) {
        throw new Error("ผลลัพธ์จาก AI มีรูปแบบไม่ตรงกับโครงสร้างหน้าเว็บ ข้อมูลเดิมของคุณยังคงอยู่ครบถ้วน");
      }
      setHistory((h) => [...h.slice(-39), blueprint]);
      setBlueprint(result.blueprint);
      if (!result.blueprint.pages.some((p) => p.id === pageId)) {
        setPageId(result.blueprint.pages[0].id);
      }
      setNotice(result.message || "AI ปรับปรุงโครงสร้างและเนื้อหาตามคำสั่งเรียบร้อยแล้ว (กด Undo ได้หากต้องการย้อนกลับ)");
    } catch (e) {
      setError(e);
      setNotice("");
    } finally {
      busyRef.current = false;
      setAiLoading(false);
    }
  };
  const triggerImageUpload = (targetPageId, targetSectionId, currentBody = "", isHero = false) => {
    if (busyRef.current || locked) return;
    uploadTargetRef.current = {
      pageId: targetPageId || page?.id,
      sectionId: targetSectionId,
      currentBody: String(currentBody || ""),
      isHero: Boolean(isHero),
    };
    if (fileInputRef.current) {
      fileInputRef.current.value = "";
      fileInputRef.current.click();
    }
  };
  const handleFileInputChange = (e) => {
    const file = e.target?.files?.[0];
    const target = uploadTargetRef.current;
    if (!file || !target || !blueprint) return;
    if (file.size > 8 * 1024 * 1024) {
      setError({ message: "ขนาดไฟล์รูปภาพต้องไม่เกิน 8 MB" });
      return;
    }
    const reader = new FileReader();
    reader.onload = async () => {
      const dataUrl = String(reader.result || "");
      if (!dataUrl.startsWith("data:image/")) {
        setError({ message: "รองรับเฉพาะไฟล์รูปภาพ JPG, PNG, WEBP, GIF หรือ AVIF" });
        return;
      }
      busyRef.current = true;
      setUploadingImage(true);
      setError(null);
      setNotice("กำลังอัปโหลดรูปภาพ…");
      try {
        const res = await builderRequest("/lab/builder/upload-image", {
          method: "POST",
          body: { data_url: dataUrl, name: file.name },
        });
        if (!res?.url) throw new Error("ไม่สามารถอัปโหลดรูปภาพได้");
        const tagLabel = target.isHero ? "Hero Image" : "Section Image";
        const newTag = `[${tagLabel}: ${res.url}]`;
        const cleaned = target.currentBody
          .replace(/\[(?:Hero|Section)\s+Image:\s*[^\]]+\]/gi, "")
          .trim();
        const nextBody = (cleaned ? `${cleaned}\n${newTag}` : newTag).slice(0, 2000);
        const next = editBlueprint(blueprint, {
          type: "update-section",
          pageId: target.pageId,
          sectionId: target.sectionId,
          patch: { body: nextBody },
        });
        setHistory((h) => [...h.slice(-39), blueprint]);
        setBlueprint(next);
        setNotice("อัปโหลดและเปลี่ยนรูปภาพเรียบร้อยแล้ว! กด Save draft เพื่อบันทึก");
      } catch (err) {
        setError(err);
        setNotice("");
      } finally {
        busyRef.current = false;
        setUploadingImage(false);
      }
    };
    reader.readAsDataURL(file);
  };
  const handleAiSectionTrigger = (targetPageId, targetSectionId, sectionHeading) => {
    if (targetPageId) setPageId(targetPageId);
    setAiScope("section");
    setAiTargetSection({ id: targetSectionId, heading: sectionHeading || "ส่วนที่เลือก" });
    setNotice(`เลือกเป้าหมาย AI: แก้ไขเฉพาะส่วน "${sectionHeading || "ส่วนที่เลือก"}" — พิมพ์คำสั่งในกล่อง ✨ สั่ง AI ด้านซ้ายได้เลย`);
  };
  const loadProjectsList = async () => {
    if (!storageReady) return;
    setLoadingProjects(true);
    try {
      const res = await builderRequest("/lab/builder/projects");
      if (Array.isArray(res?.projects)) {
        setProjectsList(res.projects);
      }
    } catch {
      // ignore
    } finally {
      setLoadingProjects(false);
    }
  };
  const deleteDraftById = async (targetUuid, draftTitle = "") => {
    if (!targetUuid || busyRef.current || locked) return;
    if (
      typeof window !== "undefined" &&
      window.confirm &&
      !window.confirm(`ยืนยันการลบแบบร่าง "${draftTitle || targetUuid}" ถาวรหรือไม่?`)
    ) {
      return;
    }
    busyRef.current = true;
    setBusy(true);
    setError(null);
    try {
      await builderRequest(`${endpoint}/${targetUuid}`, { method: "DELETE" });
      setDrafts((prev) => prev.filter((d) => d.uuid !== targetUuid));
      if (draft.uuid === targetUuid) {
        setBlueprint(null);
        setCreatedProject(null);
        setDraft({ uuid: null, revision: null });
        setSaved(null);
        setHistory([]);
        setPendingCreate(null);
        window.history.replaceState(null, "", "/lab/builder");
      }
      setNotice(`ลบแบบร่าง "${draftTitle || targetUuid}" เรียบร้อยแล้ว`);
    } catch (e) {
      setError(e);
    } finally {
      busyRef.current = false;
      setBusy(false);
    }
  };
  const deleteProjectById = async (targetProjectUuid, projectTitle = "") => {
    if (!targetProjectUuid || busyRef.current || locked) return;
    if (
      typeof window !== "undefined" &&
      window.confirm &&
      !window.confirm(`ยืนยันการลบโปรเจกต์ "${projectTitle || targetProjectUuid}" ถาวรหรือไม่? (คืนโควต้าโปรเจกต์ทันที)`)
    ) {
      return;
    }
    busyRef.current = true;
    setBusy(true);
    setError(null);
    try {
      await builderRequest(`/lab/builder/projects/${targetProjectUuid}`, { method: "DELETE" });
      setProjectsList((prev) => prev.filter((p) => p.uuid !== targetProjectUuid));
      if (createdProject?.uuid === targetProjectUuid) {
        setCreatedProject(null);
      }
      setNotice(`ลบโปรเจกต์ "${projectTitle || targetProjectUuid}" และคืนโควต้าเรียบร้อยแล้ว`);
    } catch (e) {
      setError(e);
    } finally {
      busyRef.current = false;
      setBusy(false);
    }
  };

  return (
    <div className="builder-app">
      <header className="builder-header">
        <a href="/lab" className="builder-brand">
          krikkit<span>Design workspace</span>
        </a>
        <div className="builder-header-actions">
          <span className="builder-save-state" role="status">
            {busy
              ? creatingProject ? "Creating website project…" : "Saving…"
              : loading
                ? "Loading…"
                : blueprint
                  ? dirty
                    ? "Unsaved changes"
                    : `Saved · revision ${draft.revision}`
                  : "New design draft"}
          </span>
          <a href="/lab">Back to Lab</a>
          {blueprint ? (
            <button
              type="button"
              className="builder-primary"
              onClick={save}
              disabled={
                busy ||
                loading ||
                !storageReady ||
                (!dirty && !pendingCreate) ||
                Boolean(Object.keys(validation).length)
              }
            >
              {pendingCreate && !busy
                ? "Retry save"
                : busy
                  ? "Saving…"
                  : "Save draft"}
            </button>
          ) : null}
        </div>
      </header>
      <div className="builder-shell">
        <div className="builder-intro">
          <div>
            <h1>
              {blueprint
                ? "Shape your website"
                : "Start with a clear direction"}
            </h1>
            <p>
              {blueprint
                ? "Edit the structure and see every page take shape."
                : "Choose a starting design, then make the pages and style your own."}
            </p>
          </div>
          {blueprint ? (
            <div className="builder-row-actions">
              <button
                type="button"
                className="builder-secondary"
                onClick={newDraft}
                disabled={busy || loading}
              >
                New draft
              </button>
              {draft.uuid ? (
                <button
                  type="button"
                  className="builder-danger"
                  onClick={() => deleteDraftById(draft.uuid, blueprint.title)}
                  disabled={busy || loading}
                >
                  🗑 ลบแบบร่างนี้ (Delete draft)
                </button>
              ) : null}
            </div>
          ) : null}
        </div>
        <p className="builder-disclosure">
          Save your design, then create a real React website project to build and
          preview in Lab. Creating a project uses your existing project quota.
          AI planning and data widgets are not connected here yet. Publishing is separate.
        </p>
        {blueprint ? (
          <div className="builder-row-actions" style={{ flexWrap: "wrap", gap: "8px" }}>
            <button type="button" className="builder-primary" onClick={createProject}
              disabled={locked || dirty || !draft.uuid || Boolean(Object.keys(validation).length)}>
              {creatingProject ? "Creating website project…" : "Create website project"}
            </button>
            {dirty || !draft.uuid ? <span className="builder-hint">Save your draft before creating its website project.</span> : null}
            {createdProject ? (
              <>
                <a href={`/lab/${createdProject.uuid}/workspace`}>Open website workspace · draft revision {createdProject.revision}</a>
                <button
                  type="button"
                  className="builder-danger"
                  onClick={() => deleteProjectById(createdProject.uuid, blueprint.title)}
                  disabled={locked}
                >
                  🗑 ลบโปรเจกต์ที่สร้างนี้
                </button>
              </>
            ) : null}
          </div>
        ) : null}
        {storageReady ? (
          <details
            className="builder-group"
            style={{ marginBottom: "14px", background: "#ffffff", padding: "10px 14px", borderRadius: "10px", border: "1px solid #dce4de" }}
            onToggle={(e) => {
              if (e.currentTarget.open) loadProjectsList();
            }}
          >
            <summary style={{ cursor: "pointer", fontWeight: 600, fontSize: "13px" }}>
              🗂 จัดการโปรเจกต์และแบบร่างที่ทำแล้ว (เปิดดู / ลบโปรเจกต์เพื่อคืนโควต้า / ลบแบบร่าง)
            </summary>
            <div style={{ marginTop: "10px", display: "grid", gap: "12px" }}>
              <div>
                <div style={{ display: "flex", alignItems: "center", justifyContent: "space-between", marginBottom: "6px" }}>
                  <strong style={{ fontSize: "12px" }}>โปรเจกต์เว็บไซต์ที่สร้างแล้ว (Created Projects)</strong>
                  <button type="button" onClick={loadProjectsList} disabled={loadingProjects} style={{ fontSize: "11px", padding: "3px 8px" }}>
                    {loadingProjects ? "กำลังโหลด…" : "↻ รีเฟรชรายการโปรเจกต์"}
                  </button>
                </div>
                {projectsList.length ? (
                  <div style={{ display: "grid", gap: "6px" }}>
                    {projectsList.map((proj) => (
                      <div
                        key={proj.uuid}
                        style={{
                          display: "flex",
                          alignItems: "center",
                          justifyContent: "space-between",
                          gap: "8px",
                          padding: "8px 10px",
                          borderRadius: "8px",
                          background: "#f5f8f4",
                          border: "1px solid #dce4de",
                        }}
                      >
                        <div>
                          <strong style={{ fontSize: "12px", display: "block" }}>{proj.title}</strong>
                          <small className="builder-hint">{proj.project_type} · {proj.uuid.slice(0, 8)}</small>
                        </div>
                        <div className="builder-row-actions" style={{ gap: "6px" }}>
                          <a href={proj.workspace_url} style={{ fontSize: "11px" }}>เปิด Workspace</a>
                          <button
                            type="button"
                            className="builder-danger"
                            style={{ fontSize: "11px", padding: "4px 8px" }}
                            onClick={() => deleteProjectById(proj.uuid, proj.title)}
                            disabled={locked}
                          >
                            🗑 ลบโปรเจกต์
                          </button>
                        </div>
                      </div>
                    ))}
                  </div>
                ) : (
                  <p className="builder-hint">
                    {loadingProjects ? "กำลังโหลดโปรเจกต์…" : "ยังไม่มีโปรเจกต์ที่สร้างไว้ หรือกดปุ่ม ↻ รีเฟรชรายการโปรเจกต์"}
                  </p>
                )}
              </div>
              {drafts.length ? (
                <div>
                  <strong style={{ fontSize: "12px", display: "block", marginBottom: "6px" }}>
                    แบบร่างที่บันทึกไว้ (Saved Drafts · {drafts.length})
                  </strong>
                  <div style={{ display: "grid", gap: "6px" }}>
                    {drafts.map((d) => (
                      <div
                        key={d.uuid}
                        style={{
                          display: "flex",
                          alignItems: "center",
                          justifyContent: "space-between",
                          gap: "8px",
                          padding: "8px 10px",
                          borderRadius: "8px",
                          background: "#fafbfa",
                          border: "1px solid #dce4de",
                        }}
                      >
                        <div>
                          <strong style={{ fontSize: "12px", display: "block" }}>{d.title}</strong>
                          <small className="builder-hint">{d.kind} · revision {d.revision}</small>
                        </div>
                        <div className="builder-row-actions" style={{ gap: "6px" }}>
                          <button
                            type="button"
                            style={{ fontSize: "11px", padding: "4px 8px" }}
                            onClick={() => loadDraft(d.uuid)}
                            disabled={loading}
                          >
                            เปิดแก้ไข
                          </button>
                          <button
                            type="button"
                            className="builder-danger"
                            style={{ fontSize: "11px", padding: "4px 8px" }}
                            onClick={() => deleteDraftById(d.uuid, d.title)}
                            disabled={locked}
                          >
                            🗑 ลบแบบร่าง
                          </button>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              ) : null}
            </div>
          </details>
        ) : null}
        {!storageReady ? (
          <div className="builder-alert" role="status">
            Saving is not available yet. You can explore templates and edit a
            design in this session.
          </div>
        ) : null}
        {notice ? (
          <div className="builder-alert" role="status">
            {notice}
          </div>
        ) : null}
        {error ? (
          <div className="builder-alert builder-error" role="alert">
            <strong>
              {error.status === 409
                ? "This draft has changed on the server."
                : error.status === 419
                  ? "Your session has expired."
                  : error.status === 429
                    ? "Too many requests."
                    : error.status === 503
                      ? "Draft storage is unavailable."
                      : "Your draft needs attention."}
            </strong>
            <p>{error.message}</p>
            {error.status === 409 && draft.uuid ? (
              <button
                type="button"
                onClick={() => loadDraft(draft.uuid)}
                disabled={busy || loading}
              >
                Reload saved draft (discards local edits)
              </button>
            ) : null}
            {error.errors && Object.keys(error.errors).length ? (
              <ul>
                {Object.entries(error.errors).map(([path, messages]) => (
                  <li key={path}>
                    {fieldLabel(path.replace(/^blueprint\./, ""), blueprint)}:{" "}
                    {Array.isArray(messages) ? messages.join(" ") : messages}
                  </li>
                ))}
              </ul>
            ) : null}
          </div>
        ) : null}
        {pendingCreate && !busy ? (
          <div className="builder-alert" role="status">
            The first save may have reached the server. Your exact design is
            held for a safe retry. Choose “Retry save” to recover it, or “New
            draft” to discard this local design.
          </div>
        ) : null}
        {!blueprint ? (
          <div className="builder-start">
            <main>
              <section className="builder-start-section">
                <h2>1. What kind of website?</h2>
                <div className="builder-kind-grid">
                  {catalog.kinds.map((k) => (
                    <button
                      type="button"
                      key={k.id}
                      className={
                        kind === k.id
                          ? "builder-choice selected"
                          : "builder-choice"
                      }
                      aria-pressed={kind === k.id}
                      onClick={() => {
                        setKind(k.id);
                        setTemplateId(k.template_ids[0]);
                      }}
                    >
                      <strong>{k.label}</strong>
                      <span>{k.description}</span>
                    </button>
                  ))}
                </div>
              </section>
              <section className="builder-start-section">
                <h2>2. Choose how to start</h2>
                <div className="builder-methods">
                  <div className="builder-choice selected">
                    <strong>Template</strong>
                    <span>
                      Original designs with editable pages, sections and themes.
                    </span>
                  </div>
                  <button type="button" className="builder-choice" disabled>
                    <strong>AI site builder · Coming later</strong>
                    <span>
                      AI planning is not connected. No AI credits are used here.
                    </span>
                  </button>
                </div>
                <div className="builder-template-grid">
                  {compatible.map((t) => (
                    <button
                      type="button"
                      key={t.id}
                      className={
                        templateId === t.id
                          ? "builder-template selected"
                          : "builder-template"
                      }
                      aria-pressed={templateId === t.id}
                      onClick={() => setTemplateId(t.id)}
                    >
                      <span className="builder-template-map" aria-hidden="true">
                        {t.pages.slice(0, 3).map((p, i) => (
                          <span key={p.route}>
                            <i />
                            <i />
                            <i style={{ width: `${65 - i * 12}%` }} />
                          </span>
                        ))}
                      </span>
                      <strong>{t.label}</strong>
                      <span>{t.description}</span>
                      <small>
                        {t.pages.length} pages · Editable design demo
                      </small>
                    </button>
                  ))}
                </div>
              </section>
              <section className="builder-start-section">
                <h2>3. Give your design a name</h2>
                <Field
                  label="Website title"
                  value={title}
                  onChange={setTitle}
                  limit={120}
                />
                <Field
                  label="Your website brief (optional)"
                  value={summary}
                  onChange={setSummary}
                  multiline
                  limit={2000}
                />
                <button
                  type="button"
                  className="builder-primary"
                  onClick={start}
                  disabled={loading}
                >
                  Open design workspace
                </button>
              </section>
            </main>
            <aside className="builder-resume">
              <h2>Your saved drafts</h2>
              <p>Resume one of your latest 20 designs.</p>
              {drafts.length ? (
                drafts.map((d) => (
                  <div key={d.uuid} style={{ display: "flex", gap: "6px", alignItems: "stretch", marginBottom: "6px" }}>
                    <button
                      type="button"
                      style={{ flex: 1, marginBottom: 0 }}
                      onClick={() => loadDraft(d.uuid)}
                      disabled={loading}
                    >
                      <strong>{d.title}</strong>
                      <span>
                        {d.kind} · revision {d.revision}
                      </span>
                    </button>
                    <button
                      type="button"
                      className="builder-danger"
                      style={{ width: "auto", padding: "0 10px", marginBottom: 0 }}
                      onClick={() => deleteDraftById(d.uuid, d.title)}
                      disabled={loading || busy}
                      title="ลบแบบร่างนี้"
                    >
                      🗑
                    </button>
                  </div>
                ))
              ) : (
                <p>
                  {loading
                    ? "Loading drafts…"
                    : storageReady
                      ? "No saved drafts yet. Your first design starts here."
                      : "Saved drafts will appear when storage is ready."}
                </p>
              )}
            </aside>
          </div>
        ) : (
          <div className="builder-workspace">
            <aside className="builder-editor">
              <div className="builder-editor-top">
                <h2>Design details</h2>
                <button
                  type="button"
                  onClick={undo}
                  disabled={locked || !history.length}
                >
                  Undo
                </button>
              </div>
              <p className="builder-hint">
                Undo applies to edits in this session.
              </p>
              <fieldset disabled={locked}>
                <Field
                  label="Website title"
                  value={blueprint.title}
                  onChange={(value) =>
                    edit({ type: "update-details", patch: { title: value } })
                  }
                  limit={120}
                />
                <Field
                  label="Website brief"
                  value={blueprint.summary}
                  onChange={(value) =>
                    edit({ type: "update-details", patch: { summary: value } })
                  }
                  multiline
                  limit={2000}
                />
                <input
                  ref={fileInputRef}
                  type="file"
                  accept="image/jpeg,image/png,image/webp,image/gif,image/avif"
                  style={{ display: "none" }}
                  onChange={handleFileInputChange}
                />
                <div
                  className="builder-ai-panel"
                  style={{
                    marginBottom: "14px",
                    padding: "12px",
                    borderRadius: "10px",
                    background: "linear-gradient(145deg, #eef6f0 0%, #f7faf8 100%)",
                    border: "1px solid #bcd4c5",
                  }}
                >
                  <div style={{ display: "flex", alignItems: "center", justifyContent: "space-between", gap: "8px", marginBottom: "6px" }}>
                    <strong style={{ fontSize: "13px", color: "#184f33" }}>
                      ✨ สั่ง AI ช่วยออกแบบและแก้ไขหน้าเว็บ
                    </strong>
                    {aiLoading ? (
                      <small style={{ color: "#24734b", fontWeight: 600 }}>กำลังประมวลผล AI…</small>
                    ) : null}
                  </div>
                  <p className="builder-hint" style={{ marginBottom: "8px" }}>
                    พิมพ์คำสั่งให้ AI แก้ไขเนื้อหา เพิ่มหน้า เพิ่มฟอร์ม/สินค้า/ข่าวสาร หรือเปลี่ยนโทนสีได้ทันที (กด Undo ย้อนกลับได้)
                  </p>
                  <label className="builder-field" style={{ marginBottom: "8px" }}>
                    <span>ขอบเขตที่ต้องการให้ AI แก้ไข</span>
                    <select
                      value={aiScope}
                      onChange={(e) => {
                        setAiScope(e.target.value);
                        if (e.target.value !== "section") setAiTargetSection(null);
                      }}
                    >
                      <option value="all">แก้ทั้งเว็บไซต์ (ทุกหน้า & ธีมสี)</option>
                      <option value="page">แก้เฉพาะหน้าปัจจุบัน ({page.title})</option>
                      {aiTargetSection ? (
                        <option value="section">แก้เฉพาะส่วน: {aiTargetSection.heading}</option>
                      ) : null}
                    </select>
                  </label>
                  <label className="builder-field" style={{ marginBottom: "8px" }}>
                    <span>คำสั่ง Prompt สำหรับ AI</span>
                    <textarea
                      rows={3}
                      value={aiPrompt}
                      onChange={(e) => setAiPrompt(e.target.value)}
                      placeholder="เช่น เปลี่ยนเป็นเว็บคลินิกทันตกรรม โทนสีเขียวมิ้นต์ เพิ่มฟอร์มจองคิวที่มีช่องเลือกวันที่และแนบสลิป..."
                    />
                  </label>
                  <div className="builder-row-actions" style={{ flexWrap: "wrap", gap: "6px", marginBottom: "8px" }}>
                    <button
                      type="button"
                      className="builder-primary"
                      disabled={locked}
                      onClick={() => runAiEdit(aiPrompt || blueprint.summary, aiScope)}
                    >
                      {aiLoading ? "✨ AI กำลังแก้ไขหน้าเว็บ…" : "✨ สั่ง AI แก้ไขตาม Prompt"}
                    </button>
                    {blueprint.summary ? (
                      <button
                        type="button"
                        disabled={locked}
                        onClick={() => {
                          setAiPrompt(blueprint.summary);
                          runAiEdit(blueprint.summary, "all");
                        }}
                      >
                        ใช้ Website Brief สั่ง AI
                      </button>
                    ) : null}
                  </div>
                  <div className="builder-row-actions" style={{ flexWrap: "wrap", gap: "5px" }}>
                    {[
                      ["✨ เขียนเนื้อหาใหม่ตาม Brief", blueprint.summary || `ปรับปรุงเนื้อหาทุกหน้าของ ${blueprint.shared.brand} ให้เป็นมืออาชีพและน่าเชื่อถือ`, "all"],
                      ["📝 ให้ AI เพิ่มฟอร์มจองคิว/ติดต่อ", `เพิ่มแบบฟอร์มจองคิวและติดต่อเราในหน้า ${page.title} โดยมีช่อง ชื่อ เบอร์โทร อีเมล วันที่นัดหมาย เลือกบริการ และแนบไฟล์สลิป`, "page"],
                      ["🛒 ให้ AI เพิ่มสินค้า & ตะกร้า", `เพิ่มรายการสินค้าและแพ็กเกจราคาพร้อมปุ่มตะกร้าสั่งซื้อและชำระเงิน PromptPay ในหน้า ${page.title}`, "page"],
                      ["📰 ให้ AI เขียนข่าวสาร 3 เรื่อง", `เพิ่มส่วนข่าวสารและบทความประชาสัมพันธ์ 3 เรื่องในหน้า ${page.title}`, "page"],
                    ].map(([label, presetPrompt, presetScope]) => (
                      <button
                        key={label}
                        type="button"
                        disabled={locked}
                        style={{ fontSize: "11px", padding: "4px 8px" }}
                        onClick={() => {
                          setAiPrompt(presetPrompt);
                          setAiScope(presetScope);
                          runAiEdit(presetPrompt, presetScope);
                        }}
                      >
                        {label}
                      </button>
                    ))}
                  </div>
                </div>
                <details className="builder-group" open>
                  <summary>Theme & shared content</summary>
                  <div className="builder-theme-grid">
                    {[
                      ["palette", "Palette", catalog.palettes],
                      ["font", "Typography", catalog.fonts],
                      ["radius", "Corners", catalog.radii],
                    ].map(([key, label, values]) => (
                      <label className="builder-field" key={key}>
                        <span>{label}</span>
                        <select
                          value={blueprint.theme[key]}
                          onChange={(e) =>
                            edit({
                              type: "update-theme",
                              patch: { [key]: e.target.value },
                            })
                          }
                        >
                          {Object.keys(values).map((value) => (
                            <option key={value} value={value}>
                              {typeof values[value] === "object"
                                ? values[value].label
                                : value}
                            </option>
                          ))}
                        </select>
                      </label>
                    ))}
                  </div>
                  <Field
                    label="Brand name"
                    value={blueprint.shared.brand}
                    onChange={(v) =>
                      edit({ type: "update-shared", patch: { brand: v } })
                    }
                    limit={80}
                  />
                  <Field
                    label="Footer text"
                    value={blueprint.shared.footer}
                    onChange={(v) =>
                      edit({ type: "update-shared", patch: { footer: v } })
                    }
                    limit={240}
                  />
                </details>
                <div className="builder-group">
                  <div className="builder-editor-top">
                    <h3>
                      Pages <span>{blueprint.pages.length}/5</span>
                    </h3>
                    <button
                      type="button"
                      disabled={blueprint.pages.length >= 5}
                      onClick={() => edit({ type: "add-page" })}
                    >
                      Add page
                    </button>
                  </div>
                  <div className="builder-page-tabs">
                    {blueprint.pages.map((p) => (
                      <button
                        type="button"
                        key={p.id}
                        aria-pressed={p.id === page.id}
                        onClick={() => setPageId(p.id)}
                      >
                        {p.title || "Untitled page"}
                      </button>
                    ))}
                  </div>
                  <Field
                    label="Page title"
                    value={page.title}
                    onChange={(v) =>
                      edit({
                        type: "update-page",
                        pageId: page.id,
                        patch: { title: v },
                      })
                    }
                    limit={80}
                  />
                  <Field
                    label="Page path"
                    value={page.route}
                    onChange={(v) =>
                      edit({
                        type: "update-page",
                        pageId: page.id,
                        patch: { route: v },
                      })
                    }
                    disabled={page.id === blueprint.pages[0].id}
                    limit={160}
                  />
                  <p className="builder-hint">
                    Changing the title or path updates the preview navigation.
                    Paths must be unique and lowercase.
                  </p>
                  <label className="builder-check">
                    <input
                      type="checkbox"
                      checked={page.navigation}
                      onChange={(e) =>
                        edit({
                          type: "update-page",
                          pageId: page.id,
                          patch: { navigation: e.target.checked },
                        })
                      }
                    />
                    Show in navigation
                  </label>
                  {page.id !== blueprint.pages[0].id ? (
                    <div className="builder-row-actions">
                      <button
                        type="button"
                        onClick={() =>
                          edit({
                            type: "move-page",
                            pageId: page.id,
                            to: blueprint.pages.indexOf(page) - 1,
                          })
                        }
                        disabled={blueprint.pages.indexOf(page) <= 1}
                      >
                        Move up
                      </button>
                      <button
                        type="button"
                        onClick={() =>
                          edit({
                            type: "move-page",
                            pageId: page.id,
                            to: blueprint.pages.indexOf(page) + 1,
                          })
                        }
                        disabled={
                          blueprint.pages.indexOf(page) ===
                          blueprint.pages.length - 1
                        }
                      >
                        Move down
                      </button>
                      <button
                        type="button"
                        className="builder-danger"
                        onClick={() =>
                          edit({ type: "remove-page", pageId: page.id })
                        }
                      >
                        Remove page
                      </button>
                    </div>
                  ) : null}
                </div>
                <div className="builder-group">
                  <h3>
                    Sections & Widgets ({page.title}) <span>{page.sections.length}/12</span>
                  </h3>
                  <div className="builder-widget-bar" style={{ marginBottom: "12px", padding: "10px", borderRadius: "10px", background: "#f5f8f4", border: "1px solid #dce4de" }}>
                    <strong style={{ display: "block", fontSize: "12px", marginBottom: "4px" }}>
                      + เพิ่ม Widget ลงในหน้า "{page.title}"
                    </strong>
                    <p className="builder-hint" style={{ marginBottom: "8px" }}>
                      เลือกหน้าจากแท็บ Pages ด้านบน แล้วกดปุ่มเพื่อเพิ่มแบบฟอร์ม (กำหนด field ได้), ข่าวสาร, สินค้า/ตะกร้า หรือคำถามพบบ่อยลงในหน้านั้นทันที
                    </p>
                    <div className="builder-row-actions" style={{ flexWrap: "wrap", gap: "6px" }}>
                      <button
                        type="button"
                        disabled={page.sections.length >= 12}
                        onClick={() =>
                          edit({
                            type: "add-widget",
                            pageId: page.id,
                            sectionType: "cta",
                            variant: "default",
                            heading: "แบบฟอร์มติดต่อและลงทะเบียน [widget:form]",
                            body: "กรอกข้อมูลด้านล่างเพื่อติดต่อสอบถาม สั่งซื้อ หรือจองคิวบริการ (สามารถเพิ่ม/ลบ/แก้ไข Field ได้ด้านล่าง)",
                            items: [
                              { title: "ชื่อ - นามสกุล", body: "[type:text] กรอกชื่อและนามสกุลของคุณ" },
                              { title: "เบอร์โทรศัพท์", body: "[type:tel] 08X-XXX-XXXX" },
                              { title: "อีเมลติดต่อ", body: "[type:email] name@example.com" },
                              { title: "หัวข้อที่ต้องการติดต่อ", body: "[type:select] ตัวเลือก: สอบถามบริการ, สั่งซื้อสินค้า, แจ้งชำระเงิน, อื่นๆ" },
                              { title: "รายละเอียดเพิ่มเติม", body: "[type:textarea] ระบุข้อความหรือรายละเอียดที่ต้องการแจ้ง" },
                            ],
                          })
                        }
                      >
                        + แบบฟอร์ม (กำหนด Field ได้)
                      </button>
                      <button
                        type="button"
                        disabled={page.sections.length >= 12}
                        onClick={() =>
                          edit({
                            type: "add-widget",
                            pageId: page.id,
                            sectionType: "cards",
                            variant: "default",
                            heading: "ข่าวสารและโปรโมชั่นล่าสุด [widget:news]",
                            body: "อัปเดตข่าวประชาสัมพันธ์ บทความสาระน่ารู้ และกิจกรรมใหม่ของธุรกิจ",
                            items: [
                              { title: "เปิดตัวบริการและคอลเลกชันใหม่ประจำเดือน", body: "พบกับโปรโมชั่นพิเศษและสิทธิประโยชน์สำหรับลูกค้าที่สั่งจองล่วงหน้า" },
                              { title: "อัปเดตเวลาทำการและช่องทางบริการออนไลน์", body: "รองรับการสั่งซื้อ ติดตามสถานะ และแจ้งชำระเงินผ่านระบบอัตโนมัติตลอด 24 ชม." },
                              { title: "บทความแนะนำการเลือกใช้สินค้าให้เหมาะกับคุณ", body: "เคล็ดลับและคำแนะนำจากผู้เชี่ยวชาญเพื่อความคุ้มค่าสูงสุด" },
                            ],
                          })
                        }
                      >
                        + ข่าวสาร / บทความ
                      </button>
                      <button
                        type="button"
                        disabled={page.sections.length >= 12}
                        onClick={() =>
                          edit({
                            type: "add-widget",
                            pageId: page.id,
                            sectionType: "cards",
                            variant: "default",
                            heading: "รายการสินค้าและตะกร้าสั่งซื้อ [widget:cart]",
                            body: "เลือกสินค้าลงตะกร้าและแจ้งชำระเงินผ่าน PromptPay ได้ทันที",
                            items: [
                              { title: "แพ็กเกจมาตรฐาน (Standard Set) — ฿990", body: "สินค้าคุณภาพพร้อมจัดส่งฟรีทั่วประเทศ" },
                              { title: "แพ็กเกจพรีเมียม (Premium Set) — ฿1,890", body: "ครบชุดพร้อมบริการดูแลหลังการขายพิเศษ" },
                              { title: "บริการเสริม / สั่งทำพิเศษ — ฿450", body: "ปรับแต่งตามความต้องการของลูกค้า" },
                            ],
                          })
                        }
                      >
                        + สินค้า & ตะกร้าชำระเงิน
                      </button>
                      <button
                        type="button"
                        disabled={page.sections.length >= 12}
                        onClick={() =>
                          edit({
                            type: "add-widget",
                            pageId: page.id,
                            sectionType: "feature-grid",
                            variant: "default",
                            heading: "คำถามที่พบบ่อย (FAQ)",
                            body: "รวบรวมคำตอบสำหรับข้อสงสัยในการสั่งซื้อและการให้บริการ",
                            items: [
                              { title: "สามารถชำระเงินผ่านช่องทางใดได้บ้าง?", body: "รองรับการสแกน QR PromptPay โอนผ่านธนาคาร และแนบสลิปยืนยันในหน้าเว็บ" },
                              { title: "ใช้เวลาจัดส่งหรือดำเนินการกี่วัน?", body: "จัดส่งภายใน 1–2 วันทำการหลังยืนยันการชำระเงิน" },
                            ],
                          })
                        }
                      >
                        + คำถามพบบ่อย (FAQ)
                      </button>
                    </div>
                  </div>
                  {page.sections.map((s, index) => {
                    const isFormSection = /\[widget:form\]|แบบฟอร์ม/i.test(`${s.heading} ${s.body}`);
                    return (
                    <details key={s.id} className="builder-section" open={index === page.sections.length - 1 && isFormSection}>
                      <summary>
                        {index + 1}. {s.heading || "Untitled section"}{" "}
                        <small>{isFormSection ? "form-widget" : s.type}</small>
                      </summary>
                      <label className="builder-field">
                        <span>Layout</span>
                        <select
                          value={s.variant}
                          onChange={(e) =>
                            edit({
                              type: "update-section",
                              pageId: page.id,
                              sectionId: s.id,
                              patch: { variant: e.target.value },
                            })
                          }
                        >
                          {catalog.section_types
                            .find((t) => t.id === s.type)
                            .variants.map((v) => (
                              <option key={v} value={v}>
                                {v}
                              </option>
                            ))}
                        </select>
                      </label>
                      <Field
                        label="Heading"
                        value={s.heading}
                        onChange={(v) =>
                          edit({
                            type: "update-section",
                            pageId: page.id,
                            sectionId: s.id,
                            patch: { heading: v },
                          })
                        }
                        limit={120}
                      />
                      <Field
                        label="Body"
                        value={s.body}
                        onChange={(v) =>
                          edit({
                            type: "update-section",
                            pageId: page.id,
                            sectionId: s.id,
                            patch: { body: v },
                          })
                        }
                        multiline
                        limit={2000}
                      />
                      <div className="builder-row-actions" style={{ marginBottom: "10px", flexWrap: "wrap", gap: "6px" }}>
                        <button
                          type="button"
                          onClick={() => triggerImageUpload(page.id, s.id, s.body, s.type === "hero")}
                        >
                          📷 {/\[(?:Hero|Section)\s+Image:/i.test(s.body) ? "เปลี่ยนรูปภาพ (อัปโหลดจากเครื่อง)" : "อัปโหลดรูปภาพลงส่วนนี้"}
                        </button>
                        {/\[(?:Hero|Section)\s+Image:/i.test(s.body) ? (
                          <button
                            type="button"
                            onClick={() =>
                              edit({
                                type: "update-section",
                                pageId: page.id,
                                sectionId: s.id,
                                patch: {
                                  body: s.body.replace(/\[(?:Hero|Section)\s+Image:\s*[^\]]+\]/gi, "").trim(),
                                },
                              })
                            }
                          >
                            ลบรูปภาพส่วนนี้
                          </button>
                        ) : null}
                        <button
                          type="button"
                          onClick={() => handleAiSectionTrigger(page.id, s.id, s.heading)}
                        >
                          ✨ สั่ง AI แก้ส่วนนี้
                        </button>
                      </div>
                      {s.items.map((item, i) => (
                        <div className="builder-item" key={item.id}>
                          <Field
                            label={isFormSection ? `Field ${i + 1} label (ชื่อช่องข้อมูล)` : `Item ${i + 1} title`}
                            value={item.title}
                            onChange={(v) =>
                              edit({
                                type: "update-section",
                                pageId: page.id,
                                sectionId: s.id,
                                patch: {
                                  items: s.items.map((it) =>
                                    it.id === item.id
                                      ? { ...it, title: v }
                                      : it,
                                  ),
                                },
                              })
                            }
                            limit={120}
                          />
                          {isFormSection ? (
                            <label className="builder-field">
                              <span>ประเภท Field (Field Type)</span>
                              <select
                                value={
                                  /\[type:textarea\]/i.test(item.body)
                                    ? "textarea"
                                    : /\[type:select\]/i.test(item.body)
                                      ? "select"
                                      : /\[type:email\]/i.test(item.body)
                                        ? "email"
                                        : /\[type:tel\]/i.test(item.body)
                                          ? "tel"
                                          : /\[type:date\]/i.test(item.body)
                                            ? "date"
                                            : /\[type:file\]/i.test(item.body)
                                              ? "file"
                                              : "text"
                                }
                                onChange={(e) => {
                                  const nextType = e.target.value;
                                  const stripped = String(item.body || "").replace(/\[type:[^\]]+\]\s*/gi, "");
                                  const defaultHint =
                                    nextType === "select" && !/ตัวเลือก:|options:/i.test(stripped)
                                      ? `ตัวเลือก: ตัวเลือก 1, ตัวเลือก 2, ตัวเลือก 3`
                                      : stripped;
                                  edit({
                                    type: "update-section",
                                    pageId: page.id,
                                    sectionId: s.id,
                                    patch: {
                                      items: s.items.map((it) =>
                                        it.id === item.id
                                          ? { ...it, body: `[type:${nextType}] ${defaultHint}`.slice(0, 500) }
                                          : it,
                                      ),
                                    },
                                  });
                                }}
                              >
                                <option value="text">ข้อความสั้น (Text Input)</option>
                                <option value="tel">เบอร์โทรศัพท์ (Phone)</option>
                                <option value="email">อีเมล (Email)</option>
                                <option value="select">ตัวเลือกดรอปดาวน์ (Select Dropdown)</option>
                                <option value="date">วันที่ / นัดหมาย (Date Picker)</option>
                                <option value="file">อัปโหลดไฟล์ / แนบสลิป (File Upload)</option>
                                <option value="textarea">ข้อความหลายบรรทัด (Textarea)</option>
                              </select>
                            </label>
                          ) : null}
                          <Field
                            label={isFormSection ? "Placeholder / ตัวเลือก (คั่นด้วยลูกน้ำ ,)" : "Item description"}
                            value={item.body}
                            onChange={(v) =>
                              edit({
                                type: "update-section",
                                pageId: page.id,
                                sectionId: s.id,
                                patch: {
                                  items: s.items.map((it) =>
                                    it.id === item.id ? { ...it, body: v } : it,
                                  ),
                                },
                              })
                            }
                            multiline
                            limit={500}
                          />
                          <button
                            type="button"
                            onClick={() =>
                              edit({
                                type: "update-section",
                                pageId: page.id,
                                sectionId: s.id,
                                patch: {
                                  items: s.items.filter(
                                    (it) => it.id !== item.id,
                                  ),
                                },
                              })
                            }
                          >
                            {isFormSection ? "ลบ Field นี้" : "Remove item"}
                          </button>
                        </div>
                      ))}
                      <button
                        type="button"
                        disabled={s.items.length >= 6}
                        onClick={() =>
                          edit({
                            type: "update-section",
                            pageId: page.id,
                            sectionId: s.id,
                            patch: {
                              items: [
                                ...s.items,
                                {
                                  id: crypto.randomUUID(),
                                  title: isFormSection ? `ช่องกรอกข้อมูล ${s.items.length + 1}` : "New item",
                                  body: isFormSection ? "[type:text] กรอกข้อมูล..." : "",
                                },
                              ],
                            },
                          })
                        }
                      >
                        {isFormSection ? `+ เพิ่ม Field ในฟอร์ม (${s.items.length}/6)` : `Add item (${s.items.length}/6)`}
                      </button>
                      <div className="builder-row-actions">
                        <button
                          type="button"
                          disabled={index === 0}
                          onClick={() =>
                            edit({
                              type: "move-section",
                              pageId: page.id,
                              sectionId: s.id,
                              to: index - 1,
                            })
                          }
                        >
                          Up
                        </button>
                        <button
                          type="button"
                          disabled={index === page.sections.length - 1}
                          onClick={() =>
                            edit({
                              type: "move-section",
                              pageId: page.id,
                              sectionId: s.id,
                              to: index + 1,
                            })
                          }
                        >
                          Down
                        </button>
                        <button
                          type="button"
                          disabled={page.sections.length >= 12}
                          onClick={() =>
                            edit({
                              type: "duplicate-section",
                              pageId: page.id,
                              sectionId: s.id,
                            })
                          }
                        >
                          Duplicate
                        </button>
                        <button
                          type="button"
                          className="builder-danger"
                          disabled={page.sections.length <= 1}
                          onClick={() =>
                            edit({
                              type: "remove-section",
                              pageId: page.id,
                              sectionId: s.id,
                            })
                          }
                        >
                          Remove
                        </button>
                      </div>
                    </details>
                  )})}
                  <label className="builder-field">
                    <span>Add section</span>
                    <select
                      value=""
                      disabled={page.sections.length >= 12}
                      onChange={(e) => {
                        if (e.target.value)
                          edit({
                            type: "add-section",
                            pageId: page.id,
                            sectionType: e.target.value,
                          });
                      }}
                    >
                      <option value="">Choose a section…</option>
                      {catalog.section_types.map((t) => (
                        <option key={t.id} value={t.id}>
                          {t.label}
                        </option>
                      ))}
                    </select>
                  </label>
                </div>
              </fieldset>
              {Object.keys(validation).length ? (
                <div className="builder-validation" role="status">
                  <strong>Fix these details before saving</strong>
                  <ul>
                    {Object.entries(validation).map(([path, message]) => (
                      <li key={path}>
                        {fieldLabel(path, blueprint)}: {message}
                      </li>
                    ))}
                  </ul>
                </div>
              ) : null}
            </aside>
            <main className="builder-preview">
              <div className="builder-preview-toolbar">
                <div>
                  <h2>Live design preview</h2>
                  <p>
                    {overview
                      ? "Every page, using your current content and theme."
                      : `${page.title} · ${page.route}`}
                  </p>
                </div>
                <div className="builder-view-switch">
                  <button
                    type="button"
                    aria-pressed={overview}
                    onClick={() => setOverview(true)}
                  >
                    All pages
                  </button>
                  <button
                    type="button"
                    aria-pressed={!overview}
                    onClick={() => {
                      setOverview(false);
                      setPageId(page.id);
                    }}
                  >
                    One page
                  </button>
                </div>
              </div>
              <BlueprintPreview
                blueprint={blueprint}
                catalog={catalog}
                pageId={page.id}
                onSelectPage={selectPage}
                overview={overview}
                onEdit={edit}
                onUploadImage={triggerImageUpload}
                onAiSection={handleAiSectionTrigger}
              />
            </main>
          </div>
        )}
      </div>
    </div>
  );
}
