<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ยังไม่เปิดให้รันโปรเจกต์นี้ · Krikkit</title>
    <style>
        body { margin: 0; background: #fafaf9; color: #292524; font: 16px/1.7 system-ui, sans-serif; }
        main { max-width: 40rem; margin: 8vh auto; padding: 2rem; }
        h1 { font-size: 1.6rem; line-height: 1.4; }
        p { color: #57534e; }
        a { display: inline-block; margin-top: 1rem; color: #365314; text-underline-offset: .25rem; }
        a:focus-visible { outline: 3px solid #65a30d; outline-offset: 4px; }
    </style>
</head>
<body>
<main id="runtime-unavailable">
    <p>Krikkit · Source only</p>
    <h1>ยังไม่เปิดให้รันโปรเจกต์ชนิดนี้</h1>
    <p>{{ $project->title }}</p>
    <p>{{ $runtime['reason'] }}</p>
    <p>ไฟล์เดิมยังอยู่ครบ คุณสามารถเปิดดูและแก้ไขไฟล์ในโปรเจกต์ได้</p>
    <a href="{{ route('lab.workspace', ['project' => $project->uuid]) }}" target="_blank" rel="noopener noreferrer">เปิดไฟล์โปรเจกต์ในแท็บใหม่</a>
</main>
</body>
</html>
