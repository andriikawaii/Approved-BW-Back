export default async function PreviewPage({ searchParams }: any) {
    const api = searchParams?.api as string;

    if (!api) {
        return <div style={{ padding: 24 }}>Missing api param.</div>;
    }

    const res = await fetch(api, { cache: "no-store" });
    if (!res.ok) {
        return <div style={{ padding: 24 }}>Preview fetch failed: {res.status}</div>;
    }

    const page = await res.json();

    // ovde koristiš tvoj postojeći renderer koji renderuje sections[] po type
    // npr: <PageRenderer page={page} />
    return (
        <div style={{ padding: 24 }}>
            <pre style={{ whiteSpace: "pre-wrap" }}>{JSON.stringify(page, null, 2)}</pre>
        </div>
    );
}
