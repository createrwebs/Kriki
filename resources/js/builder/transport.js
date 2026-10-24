export async function builderRequest(
  url,
  { method = "GET", body, signal } = {},
) {
  const headers = { Accept: "application/json" };
  if (method !== "GET") {
    headers["Content-Type"] = "application/json";
    headers["X-CSRF-TOKEN"] =
      document.querySelector('meta[name="csrf-token"]')?.content ?? "";
  }
  let response;
  try {
    response = await fetch(url, {
      method,
      headers,
      credentials: "same-origin",
      signal,
      ...(body === undefined ? {} : { body: JSON.stringify(body) }),
    });
  } catch (error) {
    if (error.name === "AbortError") throw error;
    throw Object.assign(
      new Error(
        "The connection was interrupted. Your edits are still here. Retry only when you are ready.",
      ),
      { status: 0, errors: {} },
    );
  }
  let data;
  try {
    data = await response.json();
  } catch {
    throw Object.assign(
      new Error(
        "The server response could not be read. Your edits are still here.",
      ),
      { status: response.status, errors: {} },
    );
  }
  if (!response.ok) {
    const errors = data?.errors === undefined ? {} : data.errors;
    const valid =
      data !== null &&
      typeof data === "object" &&
      !Array.isArray(data) &&
      typeof data.message === "string" &&
      errors !== null &&
      typeof errors === "object" &&
      !Array.isArray(errors) &&
      Object.values(errors).every(
        (messages) =>
          typeof messages === "string" ||
          (Array.isArray(messages) &&
            messages.every((message) => typeof message === "string")),
      );
    throw Object.assign(
      new Error(
        valid
          ? data.message
          : "The server response could not be read. Your edits are still here.",
      ),
      {
        status: response.status,
        errors: valid ? errors : {},
        current_revision:
          Number.isInteger(data?.current_revision) && data.current_revision > 0
            ? data.current_revision
            : undefined,
      },
    );
  }
  return data;
}
