<?php
/**
 * CreateAI / PulseAI - Next-Generation AI Cloud & High Performance Web Hosting
 * Fully integrated with WHMCS & Antler Framework
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CreateAI - ระบบสร้างเว็บไซต์ด้วย AI Prompt, เว็บโฮสติ้งความเร็วสูง & Cloud VPS</title>
    <meta name="description" content="สร้าง เปิดตัว และขยายธุรกิจออนไลน์ของคุณใน 60 วินาที ด้วยแพลตฟอร์ม Agentic AI, คลาวด์เว็บโฮสติ้งความเร็วสูง KVM VPS และระบบ AI Automation OpenClaw ดูแลตลอด 24 ชั่วโมง">
    
    <!-- Antler Core Fonts & Stylesheets -->
    <link rel="stylesheet" href="/antler/assets/fonts/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/antler/assets/fonts/fonts.min.css">
    <link rel="stylesheet" href="/antler/assets/fonts/cloudicon/cloudicon.css">
    <link rel="stylesheet" href="/antler/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/antler/assets/css/vendors.min.css">
    <link rel="stylesheet" href="/antler/assets/css/theme.min.css">
    <link rel="stylesheet" href="/antler/assets/css/aos.min.css">
    
    <!-- Google Fonts: Prompt & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Prompt:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --hostinger-purple: #673de6;
            --hostinger-dark: #0d0e1a;
            --hostinger-card-bg: #141526;
            --hostinger-card-hover: #1b1c33;
            --hostinger-border: rgba(103, 61, 230, 0.2);
            --hostinger-border-bright: #673de6;
            --accent-pink: #ee5586;
            --accent-green: #00b894;
            --accent-yellow: #ffbe00;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-dim: #64748b;
        }

        body, html {
            font-family: 'Prompt', 'Inter', -apple-system, sans-serif !important;
            background-color: var(--hostinger-dark) !important;
            color: var(--text-main) !important;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* Top Notification Bar */
        .cai-topbar {
            background: linear-gradient(90deg, #1b0a3a 0%, #30126b 50%, #1b0a3a 100%);
            border-bottom: 1px solid rgba(238, 85, 134, 0.25);
            padding: 9px 0;
            font-size: 13.5px;
            color: #e2e8f0;
        }
        .cai-topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .cai-topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
        }
        .cai-badge-promo {
            background: linear-gradient(135deg, #ee5586, #f43f5e);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .cai-topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
            white-space: nowrap;
        }
        .cai-topbar-right a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
            font-size: 13px;
        }
        .cai-topbar-right a:hover {
            color: #fdd700;
        }
        .status-dot-pulse {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: var(--accent-green);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--accent-green);
            margin-right: 6px;
        }

        /* Header & Navigation */
        .cai-header {
            background: rgba(13, 14, 26, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--hostinger-border);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .cai-header-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cai-brand-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .cai-brand-logo img {
            height: 38px;
            width: auto;
        }

        /* Nav Menu (Concise words, single line) */
        .cai-nav-menu {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 22px;
        }
        .cai-nav-item {
            position: relative;
        }
        .cai-nav-link {
            color: #f1f5f9;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            padding: 8px 4px;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: color 0.2s ease;
        }
        .cai-nav-link:hover, .cai-nav-item:hover > .cai-nav-link {
            color: #c084fc;
        }
        .cai-nav-link .arrow-ico {
            font-size: 11px;
            opacity: 0.7;
            transition: transform 0.25s ease;
        }
        .cai-nav-item:hover > .cai-nav-link .arrow-ico {
            transform: rotate(180deg);
        }
        .cai-nav-pill-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-hot {
            background: linear-gradient(135deg, #ee5586, #dc2626);
            color: #ffffff;
        }
        .badge-ai {
            background: linear-gradient(135deg, #a855f7, #673de6);
            color: #ffffff;
        }

        /* Mega Menus */
        .cai-megamenu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            background: #141526;
            border: 1px solid rgba(147, 51, 234, 0.3);
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 25px rgba(103, 61, 230, 0.2);
            padding: 20px;
            width: 580px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 1050;
        }
        .cai-megamenu.megamenu-wide {
            width: 680px;
        }
        .cai-nav-item:hover .cai-megamenu {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }
        .cai-megamenu-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .cai-megamenu-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 12px 14px;
            border-radius: 8px;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }
        .cai-megamenu-item:hover {
            background: rgba(103, 61, 230, 0.12);
            border-color: rgba(168, 85, 247, 0.3);
            transform: translateY(-2px);
        }
        .cai-megamenu-icon {
            font-size: 22px;
            color: #c084fc;
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(103, 61, 230, 0.15);
            border-radius: 8px;
        }
        .cai-megamenu-content {
            flex-grow: 1;
        }
        .cai-megamenu-title {
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 600;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cai-megamenu-title span.price-tag {
            font-size: 12px;
            color: #34d399;
            font-weight: 700;
        }
        .cai-megamenu-desc {
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
        }

        /* Action Buttons Right */
        .cai-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
        }
        .cai-btn-cart {
            position: relative;
            color: #f1f5f9;
            font-size: 18px;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .cai-btn-cart:hover {
            color: #fdd700;
            background: rgba(255, 255, 255, 0.05);
        }
        .cai-cart-counter {
            position: absolute;
            top: 2px;
            right: 4px;
            background: #ee5586;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cai-btn-login {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #f8fafc;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .cai-btn-login:hover {
            background: rgba(103, 61, 230, 0.25);
            border-color: #a855f7;
            color: #ffffff;
        }
        .cai-btn-primary {
            background: linear-gradient(135deg, #7c3aed 0%, #673de6 60%, #ee5586 100%);
            color: #ffffff !important;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(103, 61, 230, 0.4);
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }
        .cai-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(238, 85, 134, 0.5);
            color: #ffffff;
        }

        /* Hero Section */
        .cai-hero {
            position: relative;
            padding: 80px 0 65px 0;
            background: radial-gradient(circle at 50% 10%, rgba(103, 61, 230, 0.25) 0%, rgba(13, 14, 26, 0.98) 70%);
            border-bottom: 1px solid var(--hostinger-border);
            overflow: hidden;
        }
        .cai-hero-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 40px;
            align-items: center;
        }
        .cai-hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(103, 61, 230, 0.2);
            border: 1px solid rgba(168, 85, 247, 0.4);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: #e9d5ff;
            margin-bottom: 20px;
        }
        .cai-hero-title {
            font-size: 44px;
            font-weight: 800;
            line-height: 1.25;
            color: #ffffff;
            margin-bottom: 18px;
            letter-spacing: -0.5px;
        }
        .cai-hero-title .gradient-text {
            background: linear-gradient(135deg, #c084fc 0%, #ee5586 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .cai-hero-subtitle {
            font-size: 16px;
            line-height: 1.65;
            color: #94a3b8;
            margin-bottom: 30px;
            font-weight: 400;
        }
        .cai-hero-btns {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }
        .cai-btn-large {
            padding: 13px 26px;
            font-size: 15px;
            border-radius: 10px;
        }
        .cai-btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .cai-btn-secondary:hover {
            background: rgba(103, 61, 230, 0.2);
            border-color: #a855f7;
            color: #ffffff;
        }
        .cai-hero-checklist {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 13.5px;
            color: #cbd5e1;
            white-space: nowrap;
            flex-wrap: wrap;
        }
        .cai-hero-checklist span {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .cai-hero-checklist i {
            color: var(--accent-green);
        }

        /* Hero Right Card Widget */
        .cai-hero-widget {
            background: linear-gradient(145deg, #18192e 0%, #101120 100%);
            border: 1px solid rgba(168, 85, 247, 0.25);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(103, 61, 230, 0.15);
        }
        .cai-widget-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .cai-widget-stat-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 22px;
        }
        .cai-stat-box {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .cai-stat-icon {
            font-size: 20px;
            color: #c084fc;
        }
        .cai-stat-val {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
        }
        .cai-stat-lbl {
            font-size: 11px;
            color: #94a3b8;
        }
        .cai-gpu-live-banner {
            background: linear-gradient(90deg, rgba(103, 61, 230, 0.3) 0%, rgba(238, 85, 134, 0.2) 100%);
            border: 1px solid rgba(238, 85, 134, 0.4);
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Domain Search Bar */
        .cai-domain-sec {
            padding: 55px 0 65px 0;
            background: #111222;
            border-bottom: 1px solid var(--hostinger-border);
        }
        .cai-domain-box {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            padding: 0 20px;
        }
        .cai-domain-title {
            font-size: 30px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 10px;
        }
        .cai-domain-desc {
            color: #94a3b8;
            font-size: 15px;
            margin-bottom: 28px;
        }
        .cai-domain-form {
            display: flex;
            background: #181930;
            border: 2px solid rgba(168, 85, 247, 0.4);
            border-radius: 12px;
            padding: 6px 8px 6px 20px;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            margin-bottom: 22px;
        }
        .cai-domain-form i {
            color: #94a3b8;
            font-size: 18px;
            margin-right: 14px;
        }
        .cai-domain-input {
            flex-grow: 1;
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 16px;
            padding: 10px 0;
            outline: none;
            font-family: inherit;
        }
        .cai-domain-btn {
            background: linear-gradient(135deg, #fdd700 0%, #f59e0b 100%);
            color: #0f172a;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .cai-domain-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(253, 215, 0, 0.4);
        }
        .cai-tld-list {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .cai-tld-badge {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 13.5px;
            color: #e2e8f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cai-tld-badge b {
            color: #38bdf8;
        }

        /* 4 PILLARS WORKFLOW (BUILD, LAUNCH, GROW, MANAGE) */
        .cai-workflow-sec {
            padding: 85px 0 75px 0;
            background: linear-gradient(180deg, #0d0e1a 0%, #121324 100%);
            border-bottom: 1px solid var(--hostinger-border);
            position: relative;
        }
        .cai-workflow-tabs {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .cai-workflow-tab-btn {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            padding: 11px 24px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cai-workflow-tab-btn:hover {
            background: rgba(103, 61, 230, 0.2);
            color: #ffffff;
            border-color: #a855f7;
        }
        .cai-workflow-tab-btn.active {
            background: linear-gradient(135deg, #7c3aed 0%, #673de6 60%, #ee5586 100%);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 4px 20px rgba(103, 61, 230, 0.5);
        }
        .cai-workflow-panel {
            display: none;
            max-width: 1200px;
            margin: 0 auto;
            background: #15162a;
            border: 1px solid rgba(168, 85, 247, 0.25);
            border-radius: 20px;
            padding: 45px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            animation: fadeInTab 0.35s ease forwards;
        }
        .cai-workflow-panel.active {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 40px;
            align-items: center;
        }
        @keyframes fadeInTab {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .cai-wf-content h3 {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 16px;
            line-height: 1.35;
        }
        .cai-wf-content p {
            font-size: 15.5px;
            color: #94a3b8;
            line-height: 1.65;
            margin-bottom: 24px;
        }
        .cai-wf-points {
            list-style: none;
            padding: 0;
            margin: 0 0 26px 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .cai-wf-points li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #e2e8f0;
            font-size: 14.5px;
        }
        .cai-wf-points li i {
            color: var(--accent-green);
            font-size: 16px;
        }
        .cai-wf-preview-box {
            background: #0d0e1a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 24px;
            position: relative;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.6);
        }
        .cai-wf-screen-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .wf-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .wf-dot-red { background: #ef4444; }
        .wf-dot-yellow { background: #f59e0b; }
        .wf-dot-green { background: #10b981; }

        /* OPENCLAW AI AUTOMATION SPOTLIGHT SECTION */
        .cai-openclaw-sec {
            padding: 85px 0;
            background: radial-gradient(circle at 50% 30%, rgba(103, 61, 230, 0.22) 0%, rgba(13, 14, 26, 0.98) 70%);
            border-bottom: 1px solid var(--hostinger-border);
            position: relative;
        }
        .cai-openclaw-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .cai-openclaw-card {
            background: linear-gradient(145deg, #181932 0%, #121324 100%);
            border: 2px solid #8b5cf6;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 25px 60px rgba(103, 61, 230, 0.35);
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 45px;
            align-items: center;
        }
        .cai-oc-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #ee5586, #dc2626);
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 18px;
        }
        .cai-oc-title {
            font-size: 34px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.3;
            margin-bottom: 16px;
        }
        .cai-oc-desc {
            font-size: 16px;
            color: #cbd5e1;
            line-height: 1.65;
            margin-bottom: 26px;
        }
        .cai-oc-feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 30px;
        }
        .cai-oc-feat-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .cai-oc-feat-item i {
            color: #34d399;
            font-size: 16px;
            margin-top: 3px;
        }
        .cai-oc-feat-item strong {
            display: block;
            color: #ffffff;
            font-size: 14.5px;
            margin-bottom: 2px;
        }
        .cai-oc-feat-item span {
            color: #94a3b8;
            font-size: 12.5px;
            line-height: 1.4;
        }
        .cai-oc-pricing-box {
            background: #111224;
            border: 1px solid rgba(168, 85, 247, 0.3);
            border-radius: 18px;
            padding: 34px 28px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }
        .cai-oc-discount-pill {
            background: rgba(238, 85, 134, 0.2);
            color: #ee5586;
            font-size: 13px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 14px;
        }
        .cai-oc-price-row {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 6px;
            margin-bottom: 6px;
        }
        .cai-oc-currency {
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
        }
        .cai-oc-num {
            font-size: 52px;
            font-weight: 800;
            color: #fdd700;
            line-height: 1;
        }
        .cai-oc-period {
            font-size: 16px;
            color: #94a3b8;
        }
        .cai-oc-renew {
            font-size: 12.5px;
            color: #94a3b8;
            margin-bottom: 24px;
        }
        .cai-oc-btn {
            background: linear-gradient(135deg, #7c3aed 0%, #ee5586 100%);
            color: #ffffff !important;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            display: block;
            width: 100%;
            box-shadow: 0 4px 20px rgba(238, 85, 134, 0.5);
            transition: all 0.25s ease;
        }
        .cai-oc-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(238, 85, 134, 0.7);
        }

        /* 4-COLUMN ALIGNED PRICING MATRIX */
        .cai-pricing-sec {
            padding: 85px 0;
            background: var(--hostinger-dark);
            position: relative;
        }
        .cai-section-header {
            text-align: center;
            max-width: 820px;
            margin: 0 auto 50px auto;
            padding: 0 20px;
        }
        .cai-section-tag {
            color: #c084fc;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: inline-block;
        }
        .cai-section-title {
            font-size: 36px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 14px;
            letter-spacing: -0.5px;
        }
        .cai-section-subtitle {
            font-size: 16px;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* The 4-Column Grid */
        .cai-pricing-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
            align-items: stretch;
        }

        /* Pricing Card */
        .cai-price-card {
            background: var(--hostinger-card-bg);
            border: 1px solid var(--hostinger-border);
            border-radius: 16px;
            padding: 26px 22px;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .cai-price-card:hover {
            transform: translateY(-6px);
            border-color: #a855f7;
            box-shadow: 0 20px 40px rgba(103, 61, 230, 0.25);
            background: var(--hostinger-card-hover);
        }
        .cai-price-card.featured {
            border: 2px solid #8b5cf6;
            background: linear-gradient(180deg, #191733 0%, #131427 100%);
            box-shadow: 0 15px 40px rgba(139, 92, 246, 0.3);
            transform: scale(1.02);
            z-index: 2;
        }
        .cai-price-card.featured:hover {
            transform: scale(1.02) translateY(-6px);
            box-shadow: 0 25px 50px rgba(139, 92, 246, 0.45);
        }

        /* Fixed Row Heights for Strict Alignment */
        .card-row-badge {
            height: 26px;
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        .badge-card {
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .badge-standard {
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
        }
        .badge-popular {
            background: linear-gradient(135deg, #ee5586, #f43f5e);
            color: #ffffff;
            box-shadow: 0 2px 10px rgba(238, 85, 134, 0.4);
        }
        .badge-vps {
            background: linear-gradient(135deg, #0284c7, #2563eb);
            color: #ffffff;
        }
        .badge-gpu {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
        }

        .card-row-title {
            height: 32px;
            display: flex;
            align-items: center;
            font-size: 21px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
            white-space: nowrap;
        }

        .card-row-desc {
            height: 42px;
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.45;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
        }

        .card-row-price {
            height: 60px;
            display: flex;
            align-items: baseline;
            gap: 6px;
            margin-bottom: 6px;
            white-space: nowrap;
        }
        .price-currency {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
        }
        .price-number {
            font-size: 44px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1;
        }
        .price-period {
            font-size: 14px;
            color: #94a3b8;
            font-weight: 400;
        }

        .card-row-save {
            height: 28px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 12.5px;
            white-space: nowrap;
        }
        .save-pill {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 700;
        }
        .strike-price {
            text-decoration: line-through;
            color: #64748b;
        }

        .card-row-btn {
            height: 46px;
            margin-bottom: 24px;
        }
        .card-btn {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14.5px;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .card-btn-default {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }
        .card-btn-default:hover {
            background: rgba(103, 61, 230, 0.3);
            border-color: #a855f7;
            color: #ffffff;
        }
        .card-btn-featured {
            background: linear-gradient(135deg, #7c3aed 0%, #ee5586 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(238, 85, 134, 0.4);
        }
        .card-btn-featured:hover {
            box-shadow: 0 8px 25px rgba(238, 85, 134, 0.6);
            transform: scale(1.02);
            color: #ffffff;
        }

        .card-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin-bottom: 20px;
        }
        .card-feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 13px;
            font-size: 13px;
            color: #cbd5e1;
            flex-grow: 1;
        }
        .card-feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            line-height: 1.4;
        }
        .card-feature-item i {
            color: var(--accent-green);
            font-size: 14px;
            flex-shrink: 0;
        }
        .card-feature-item b {
            color: #ffffff;
        }

        /* VPS INTERACTIVE SLIDER SECTION */
        .cai-slider-sec {
            padding: 80px 0;
            background: #111224;
            border-top: 1px solid var(--hostinger-border);
            border-bottom: 1px solid var(--hostinger-border);
        }
        .cai-slider-box {
            max-width: 960px;
            margin: 0 auto;
            background: #16172d;
            border: 1px solid rgba(168, 85, 247, 0.25);
            border-radius: 18px;
            padding: 38px 40px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5);
        }
        .cai-slider-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            position: relative;
        }
        .cai-step-marker {
            cursor: pointer;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            transition: all 0.2s ease;
            position: relative;
            z-index: 2;
        }
        .cai-step-marker.active {
            color: #ffffff;
        }
        .cai-step-marker.active .dot-marker {
            background: #fdd700;
            box-shadow: 0 0 12px #fdd700;
            transform: scale(1.3);
        }
        .dot-marker {
            width: 14px;
            height: 14px;
            background: #334155;
            border-radius: 50%;
            margin: 0 auto 8px auto;
            transition: all 0.2s ease;
        }
        .cai-range-bar {
            width: 100%;
            -webkit-appearance: none;
            height: 8px;
            border-radius: 4px;
            background: #334155;
            outline: none;
            margin-bottom: 35px;
            cursor: pointer;
        }
        .cai-range-bar::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #fdd700;
            cursor: pointer;
            box-shadow: 0 0 14px rgba(253, 215, 0, 0.6);
            transition: transform 0.15s ease;
        }
        .cai-range-bar::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }
        .cai-slider-result-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr) 1.2fr;
            gap: 16px;
            align-items: center;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 22px;
        }
        .cai-slider-spec-col {
            text-align: center;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            padding-right: 12px;
        }
        .cai-spec-number {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 2px;
        }
        .cai-spec-label {
            font-size: 12px;
            color: #94a3b8;
        }
        .cai-slider-buy-col {
            text-align: center;
            padding-left: 10px;
        }
        .cai-slider-price-now {
            font-size: 28px;
            font-weight: 800;
            color: #fdd700;
            margin-bottom: 8px;
        }

        /* GPU AI CLOUD ON-DEMAND SHOWCASE */
        .cai-gpu-sec {
            padding: 85px 0;
            background: radial-gradient(circle at 50% 50%, rgba(238, 85, 134, 0.1) 0%, rgba(13, 14, 26, 0.98) 75%);
            border-bottom: 1px solid var(--hostinger-border);
        }
        .cai-gpu-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1240px;
            margin: 0 auto 40px auto;
            padding: 0 20px;
        }
        .cai-gpu-card {
            background: #15162a;
            border: 1px solid rgba(238, 85, 134, 0.3);
            border-radius: 16px;
            padding: 28px 24px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .cai-gpu-card:hover {
            transform: translateY(-6px);
            border-color: #ee5586;
            box-shadow: 0 15px 35px rgba(238, 85, 134, 0.25);
        }
        .cai-gpu-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .cai-gpu-model {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
        }
        .cai-gpu-vram-tag {
            background: rgba(168, 85, 247, 0.2);
            color: #c084fc;
            border: 1px solid rgba(168, 85, 247, 0.4);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }
        .cai-gpu-price-hourly {
            font-size: 26px;
            font-weight: 800;
            color: #38bdf8;
            margin-bottom: 12px;
        }
        .cai-gpu-price-hourly span {
            font-size: 13px;
            font-weight: 400;
            color: #94a3b8;
        }
        .cai-gpu-features {
            list-style: none;
            padding: 0;
            margin: 0 0 24px 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 13px;
            color: #cbd5e1;
            flex-grow: 1;
        }
        .cai-gpu-features li {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cai-gpu-features li i {
            color: var(--accent-green);
        }

        /* FEATURE COMPARISON TABLE */
        .cai-compare-sec {
            padding: 85px 0;
            background: #0d0e1a;
            border-bottom: 1px solid var(--hostinger-border);
        }
        .cai-compare-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            overflow-x: auto;
        }
        .cai-compare-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            color: #cbd5e1;
            background: #141528;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--hostinger-border);
        }
        .cai-compare-table th {
            background: #1c1d38;
            padding: 18px 20px;
            font-weight: 700;
            color: #ffffff;
            text-align: left;
            border-bottom: 2px solid rgba(168, 85, 247, 0.3);
            white-space: nowrap;
        }
        .cai-compare-table td {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }
        .cai-compare-table tr:hover td {
            background: rgba(103, 61, 230, 0.08);
        }
        .cai-compare-table th.col-highlight,
        .cai-compare-table td.col-highlight {
            background: rgba(103, 61, 230, 0.12);
            border-left: 1px solid rgba(168, 85, 247, 0.25);
            border-right: 1px solid rgba(168, 85, 247, 0.25);
            font-weight: 600;
            color: #ffffff;
        }
        .icon-check {
            color: var(--accent-green);
            font-size: 15px;
        }
        .icon-minus {
            color: #64748b;
            font-size: 12px;
        }

        /* WHY CHOOSE US (6 PILLARS) */
        .cai-why-sec {
            padding: 85px 0;
            background: #111224;
            border-bottom: 1px solid var(--hostinger-border);
        }
        .cai-why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .cai-why-card {
            background: #15162a;
            border: 1px solid var(--hostinger-border);
            border-radius: 16px;
            padding: 30px 24px;
            transition: all 0.25s ease;
        }
        .cai-why-card:hover {
            border-color: #a855f7;
            transform: translateY(-4px);
            background: #1a1b35;
        }
        .cai-why-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(103, 61, 230, 0.2);
            color: #c084fc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
        }
        .cai-why-title {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 10px;
        }
        .cai-why-desc {
            font-size: 14px;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* NEWS & ANNOUNCEMENTS */
        .cai-news-sec {
            padding: 80px 0;
            background: #0d0e1a;
            border-bottom: 1px solid var(--hostinger-border);
        }
        .cai-news-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .cai-news-card {
            background: #141526;
            border: 1px solid var(--hostinger-border);
            border-radius: 14px;
            padding: 24px;
            text-decoration: none;
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
        }
        .cai-news-card:hover {
            transform: translateY(-4px);
            border-color: #a855f7;
            background: #191a32;
        }
        .cai-news-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 12px;
        }
        .cai-news-tag {
            background: rgba(103, 61, 230, 0.25);
            color: #c084fc;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .cai-news-title {
            color: #ffffff;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.45;
        }
        .cai-news-desc {
            color: #94a3b8;
            font-size: 13.5px;
            line-height: 1.55;
            margin-bottom: 18px;
            flex-grow: 1;
        }
        .cai-news-more {
            color: #c084fc;
            font-size: 13.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* FAQ SECTION */
        .cai-faq-sec {
            padding: 85px 0;
            background: #111224;
            border-bottom: 1px solid var(--hostinger-border);
        }
        .cai-faq-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .cai-faq-item {
            background: #16172d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        .cai-faq-item.active {
            border-color: #a855f7;
            box-shadow: 0 6px 20px rgba(103, 61, 230, 0.2);
        }
        .cai-faq-question {
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            user-select: none;
        }
        .cai-faq-question i {
            color: #c084fc;
            transition: transform 0.25s ease;
        }
        .cai-faq-item.active .cai-faq-question i {
            transform: rotate(180deg);
        }
        .cai-faq-answer {
            display: none;
            padding: 0 22px 20px 22px;
            font-size: 14.5px;
            color: #94a3b8;
            line-height: 1.65;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            padding-top: 14px;
        }
        .cai-faq-item.active .cai-faq-answer {
            display: block;
        }

        /* FOOTER */
        .cai-footer {
            background: #090a14;
            padding: 70px 0 30px 0;
            font-size: 13.5px;
            color: #94a3b8;
        }
        .cai-footer-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }
        .cai-footer-col h4 {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .cai-footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .cai-footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .cai-footer-links a:hover {
            color: #c084fc;
        }
        .cai-footer-bottom {
            max-width: 1240px;
            margin: 0 auto;
            padding: 25px 20px 0 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }
        .cai-payments-list {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 24px;
            color: #cbd5e1;
        }

        /* Mobile & Responsive Adjustments */
        @media (max-width: 1024px) {
            .cai-pricing-grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
            .cai-hero-inner {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .cai-hero-checklist {
                justify-content: center;
            }
            .cai-workflow-panel.active {
                grid-template-columns: 1fr;
            }
            .cai-openclaw-card {
                grid-template-columns: 1fr;
            }
            .cai-why-grid {
                grid-template-columns: 1fr 1fr;
            }
            .cai-slider-result-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            .cai-slider-buy-col {
                grid-column: span 2;
                border-top: 1px solid rgba(255, 255, 255, 0.08);
                padding-top: 16px;
            }
            .cai-gpu-grid-3 {
                grid-template-columns: 1fr;
            }
            .cai-news-grid {
                grid-template-columns: 1fr;
            }
            .cai-footer-inner {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 768px) {
            .cai-pricing-grid-4 {
                grid-template-columns: 1fr;
            }
            .cai-why-grid {
                grid-template-columns: 1fr;
            }
            .cai-topbar-right {
                display: none;
            }
            .cai-nav-menu {
                display: none;
            }
            .cai-hero-title {
                font-size: 32px;
            }
            .cai-footer-inner {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body <?php body_class('cai-antler-theme'); ?>>

<!-- 1. Top Infobar -->
<div class="cai-topbar">
    <div class="cai-topbar-inner">
        <div class="cai-topbar-left">
            <span class="cai-badge-promo">⚡ PROMO พิเศษ</span>
            <span>เปิดตัวระบบ AI Cloud! เว็บโฮสติ้งเริ่มต้น <strong>฿29/ด.</strong> | แอป AI Automation <strong>฿199/ด.</strong> + คืนเงินใน 30 วัน</span>
        </div>
        <div class="cai-topbar-right">
            <span><span class="status-dot-pulse"></span> สถานะเครือข่าย: ปกติ 100% (Bangkok Node)</span>
            <a href="/billing/index.php?rp=/announcements"><i class="fas fa-bullhorn"></i> ข่าวสาร</a>
            <a href="/billing/contact.php"><i class="fas fa-headset"></i> ติดต่อ 24/7</a>
            <a href="/billing/clientarea.php"><i class="fas fa-user-lock"></i> สมาชิก WHMCS</a>
        </div>
    </div>
</div>

<!-- 2. Header & Mega Menu Navigation -->
<header class="cai-header">
    <div class="cai-header-inner">
        <!-- Logo -->
        <a href="/" class="cai-brand-logo" title="CreateAI Cloud">
            <img src="/antler/assets/img/logo.svg" alt="CreateAI Cloud" width="180" height="45">
        </a>

        <!-- Main Nav with Short Words & Mega Menus -->
        <nav>
            <ul class="cai-nav-menu">
                <!-- Menu Item: AI Website Builder -->
                <li class="cai-nav-item">
                    <a href="/lab" class="cai-nav-link" style="background: linear-gradient(135deg, rgba(147, 51, 234, 0.25), rgba(238, 85, 134, 0.25)); border: 1px solid #a855f7; border-radius: 8px; padding: 6px 14px; color: #ffffff; font-weight: 600;">
                        <i class="fas fa-wand-magic-sparkles" style="color: #fdd700; margin-right: 4px;"></i> ระบบสร้างเว็บด้วย AI Prompt <span class="cai-nav-pill-badge badge-hot" style="margin-left: 6px;">NEW</span>
                    </a>
                </li>

                <li class="cai-nav-item">
                    <a href="#pricing" class="cai-nav-link">ราคา</a>
                </li>

                <!-- Mega Menu: สินค้า -->
                <li class="cai-nav-item">
                    <a href="/billing/index.php?rp=/store" class="cai-nav-link">
                        สินค้า <i class="fas fa-chevron-down arrow-ico"></i>
                    </a>
                    <div class="cai-megamenu">
                        <div class="cai-megamenu-grid">
                            <a href="/billing/cart.php?a=add&pid=8" class="cai-megamenu-item">
                                <div class="cai-megamenu-icon"><i class="fas fa-globe"></i></div>
                                <div class="cai-megamenu-content">
                                    <div class="cai-megamenu-title">Single Web Hosting <span class="price-tag">฿29/ด.</span></div>
                                    <p class="cai-megamenu-desc">สำหรับ 1 เว็บไซต์ เริ่มต้นเบาๆ ฟรี SSL ตลอดชีพ</p>
                                </div>
                            </a>
                            <a href="/billing/cart.php?a=add&pid=2" class="cai-megamenu-item">
                                <div class="cai-megamenu-icon"><i class="fas fa-bolt"></i></div>
                                <div class="cai-megamenu-content">
                                    <div class="cai-megamenu-title">Premium Web <span class="cai-nav-pill-badge badge-hot">฿59/ด.</span></div>
                                    <p class="cai-megamenu-desc">100 เว็บไซต์ ฟรีโดเมน 1 ปี SSD สปีดสูง</p>
                                </div>
                            </a>
                            <a href="/billing/cart.php?a=add&pid=3" class="cai-megamenu-item">
                                <div class="cai-megamenu-icon"><i class="fas fa-briefcase"></i></div>
                                <div class="cai-megamenu-content">
                                    <div class="cai-megamenu-title">Business Hosting <span class="price-tag">฿89/ด.</span></div>
                                    <p class="cai-megamenu-desc">ธุรกิจออนไลน์ แคชขั้นสูง สำรองข้อมูลรายวัน</p>
                                </div>
                            </a>
                            <a href="/billing/cart.php?a=add&pid=4" class="cai-megamenu-item">
                                <div class="cai-megamenu-icon"><i class="fas fa-cloud"></i></div>
                                <div class="cai-megamenu-content">
                                    <div class="cai-megamenu-title">Cloud Startup <span class="price-tag">฿209/ด.</span></div>
                                    <p class="cai-megamenu-desc">Dedicated IP ส่วนตัว สปีดระดับองค์กร</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Link: ตัวแทน AI (OpenClaw) -->
                <li class="cai-nav-item">
                    <a href="#openclaw" class="cai-nav-link">
                        ตัวแทน AI <span class="cai-nav-pill-badge badge-ai">HOT</span>
                    </a>
                </li>

                <!-- Mega Menu: โซลูชัน -->
                <li class="cai-nav-item">
                    <a href="#why-choose" class="cai-nav-link">
                        โซลูชัน <i class="fas fa-chevron-down arrow-ico"></i>
                    </a>
                    <div class="cai-megamenu" style="width: 440px;">
                        <a href="#pricing" class="cai-megamenu-item">
                            <div class="cai-megamenu-icon"><i class="fas fa-store"></i></div>
                            <div class="cai-megamenu-content">
                                <div class="cai-megamenu-title">สำหรับธุรกิจ & ร้านค้า</div>
                                <p class="cai-megamenu-desc">เปิดร้านออนไลน์ ระบบเสถียร ไม่ล่ม รับทราฟฟิกมหาศาล</p>
                            </div>
                        </a>
                        <a href="#openclaw" class="cai-megamenu-item mt-2">
                            <div class="cai-megamenu-icon"><i class="fas fa-magic"></i></div>
                            <div class="cai-megamenu-content">
                                <div class="cai-megamenu-title">สำหรับงานอัตโนมัติ (Automation)</div>
                                <p class="cai-megamenu-desc">ใช้ตัวแทน AI ช่วยตอบลูกค้า ส่งอีเมล และดึงข้อมูล 24 ชม.</p>
                            </div>
                        </a>
                    </div>
                </li>

                <!-- Mega Menu: นักพัฒนา -->
                <li class="cai-nav-item">
                    <a href="/billing/index.php?rp=/store/cloud-vps" class="cai-nav-link">
                        นักพัฒนา <i class="fas fa-chevron-down arrow-ico"></i>
                    </a>
                    <div class="cai-megamenu megamenu-wide">
                        <div class="cai-megamenu-grid">
                            <a href="/billing/cart.php?a=add&pid=5" class="cai-megamenu-item">
                                <div class="cai-megamenu-icon"><i class="fas fa-server"></i></div>
                                <div class="cai-megamenu-content">
                                    <div class="cai-megamenu-title">KVM 1 VPS <span class="price-tag">฿199/ด.</span></div>
                                    <p class="cai-megamenu-desc">1 vCPU, 4GB RAM, 50GB NVMe SSD สิทธิ์ Root</p>
                                </div>
                            </a>
                            <a href="/billing/cart.php?a=add&pid=6" class="cai-megamenu-item">
                                <div class="cai-megamenu-icon"><i class="fas fa-microchip"></i></div>
                                <div class="cai-megamenu-content">
                                    <div class="cai-megamenu-title">KVM 2 VPS <span class="cai-nav-pill-badge badge-hot">฿299/ด.</span></div>
                                    <p class="cai-megamenu-desc">2 vCPU, 8GB RAM, 100GB NVMe Gen4 สปีดจัดเต็ม</p>
                                </div>
                            </a>
                            <a href="/billing/gpu_cloud.php" class="cai-megamenu-item">
                                <div class="cai-megamenu-icon" style="background: rgba(238, 85, 134, 0.2); color: #ee5586;"><i class="fas fa-robot"></i></div>
                                <div class="cai-megamenu-content">
                                    <div class="cai-megamenu-title">On-Demand GPU Cloud <span class="price-tag">฿4.20/ชม.</span></div>
                                    <p class="cai-megamenu-desc">RTX 4090 / 5090 / A100 รัน DeepSeek, vLLM, ComfyUI</p>
                                </div>
                            </a>
                            <a href="/billing/index.php?rp=/store/cloud-vps" class="cai-megamenu-item">
                                <div class="cai-megamenu-icon"><i class="fas fa-terminal"></i></div>
                                <div class="cai-megamenu-content">
                                    <div class="cai-megamenu-title">CLI & Docker Engine</div>
                                    <p class="cai-megamenu-desc">รองรับคอนเทนเนอร์ คอนฟิกเน็ตเวิร์ก และ API เต็มรูปแบบ</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Link: ทรัพยากร -->
                <li class="cai-nav-item">
                    <a href="/documentation/" class="cai-nav-link">ทรัพยากร</a>
                </li>
            </ul>
        </nav>

        <!-- Header Actions -->
        <div class="cai-header-actions">
            <a href="/billing/cart.php?a=view" class="cai-btn-cart" title="ตะกร้าสินค้า">
                <i class="fas fa-shopping-cart"></i>
                <span class="cai-cart-counter">0</span>
            </a>
            <a href="/billing/clientarea.php" class="cai-btn-login">
                <i class="fas fa-user-circle"></i> เข้าสู่ระบบ
            </a>
            <a href="#pricing" class="cai-btn-primary">
                เริ่มต้นใช้งาน <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</header>

<!-- 3. Hero Section -->
<section class="cai-hero">
    <div class="cai-hero-inner">
        <div class="cai-hero-text">
            <div class="cai-hero-tag">
                <i class="fas fa-sparkles text-warning"></i> ระบบสำหรับสร้าง WEBSITE ด้วย AI PROMPT & WEB CLOUD ชั้นนำ
            </div>
            <h1 class="cai-hero-title">
                ระบบสร้าง Website อัจฉริยะด้วย <br><span class="gradient-text">AI Prompt ใน 60 วินาที</span>
            </h1>
            <p class="cai-hero-subtitle">
                เพียงแค่พิมพ์ข้อความคำสั่ง (Prompt) ที่คุณต้องการ ระบบ AI จะสร้างเว็บไซต์ ดีไซน์หน้าเว็บ วางเลย์เอาต์ และเลือกรูปภาพให้ทันที พร้อมเว็บโฮสติ้งความเร็วสูงระดับ Enterprise, คลาวด์ VPS KVM และระบบบริหารจัดการครบวงจร
            </p>
            <div class="cai-hero-btns">
                <a href="/lab" class="cai-btn-primary cai-btn-large" style="background: linear-gradient(135deg, #a855f7 0%, #ee5586 100%); box-shadow: 0 4px 25px rgba(238, 85, 134, 0.5);">
                    <i class="fas fa-wand-magic-sparkles"></i> เริ่มต้นสร้างเว็บด้วย AI Prompt <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#pricing" class="cai-btn-secondary cai-btn-large">
                    <i class="fas fa-server"></i> ดูแพ็กเกจเว็บโฮสติ้ง & VPS (เริ่ม ฿29/ด.)
                </a>
            </div>
            <div class="cai-hero-checklist">
                <span><i class="fas fa-check-circle"></i> รับประกันคืนเงินใน 30 วัน</span>
                <span><i class="fas fa-check-circle"></i> ฟรี โดเมนเนม 1 ปีเต็ม</span>
                <span><i class="fas fa-check-circle"></i> ย้ายเว็บไซต์ฟรี 100%</span>
                <span><i class="fas fa-check-circle"></i> วิศวกรซัพพอร์ต 24/7</span>
            </div>
        </div>

        <!-- Hero Right Floating Tech Card -->
        <div class="cai-hero-widget">
            <div class="cai-widget-header">
                <div>
                    <div style="font-weight: 700; color: #ffffff; font-size: 16px;">Node: Bangkok Datacenter</div>
                    <div style="font-size: 12.5px; color: #94a3b8;">CreateAI Cloud Infrastructure Live Status</div>
                </div>
                <div style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 50px;">
                    <span class="status-dot-pulse"></span> ONLINE 100%
                </div>
            </div>

            <div class="cai-widget-stat-grid">
                <div class="cai-stat-box">
                    <div class="cai-stat-icon"><i class="fas fa-tachometer-alt"></i></div>
                    <div>
                        <div class="cai-stat-val">7,450 MB/s</div>
                        <div class="cai-stat-lbl">NVMe Gen4 Read Speed</div>
                    </div>
                </div>
                <div class="cai-stat-box">
                    <div class="cai-stat-icon"><i class="fas fa-wifi" style="color: #38bdf8;"></i></div>
                    <div>
                        <div class="cai-stat-val">&lt; 8 ms</div>
                        <div class="cai-stat-lbl">Local Low Latency</div>
                    </div>
                </div>
                <div class="cai-stat-box">
                    <div class="cai-stat-icon"><i class="fas fa-shield-alt" style="color: #f59e0b;"></i></div>
                    <div>
                        <div class="cai-stat-val">100 Gbps</div>
                        <div class="cai-stat-lbl">Cloudflare DDoS Shield</div>
                    </div>
                </div>
                <div class="cai-stat-box">
                    <div class="cai-stat-icon"><i class="fas fa-bolt" style="color: #ee5586;"></i></div>
                    <div>
                        <div class="cai-stat-val">LiteSpeed</div>
                        <div class="cai-stat-lbl">Enterprise Web Server</div>
                    </div>
                </div>
            </div>

            <!-- Agent Ribbon -->
            <div class="cai-gpu-live-banner">
                <div>
                    <div style="font-size: 12px; color: #e9d5ff; font-weight: 600;">AGENTIC AUTOMATION POWERED</div>
                    <div style="font-size: 14.5px; font-weight: 700; color: #ffffff;">OpenClaw AI 24/7 Agent Active</div>
                </div>
                <a href="#openclaw" class="btn btn-sm btn-default-yellow-fill">
                    ดูตัวแทน AI <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 4. Domain Search Bar -->
<section class="cai-domain-sec">
    <div class="cai-domain-box">
        <h2 class="cai-domain-title">ค้นหาและจดทะเบียนชื่อโดเมนของคุณ</h2>
        <p class="cai-domain-desc">สร้างตัวตนบนโลกออนไลน์ทันที รับฟรีระบบ DNS ความเร็วสูง และความเป็นส่วนตัว WHOIS Protection</p>
        
        <form action="/billing/cart.php?a=add&domain=register" method="post" class="cai-domain-form">
            <i class="fas fa-search"></i>
            <input type="text" name="query" placeholder="พิมพ์ชื่อโดเมนที่คุณต้องการ เช่น yourbrand.com" class="cai-domain-input" required>
            <button type="submit" class="cai-domain-btn">
                ค้นหาโดเมน <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </form>

        <div class="cai-tld-list">
            <div class="cai-tld-badge">.com <b>฿390/ปี</b></div>
            <div class="cai-tld-badge">.site <b style="color: #ee5586;">฿150/ปี (ลดพิเศษ)</b></div>
            <div class="cai-tld-badge">.online <b style="color: #fdd700;">฿120/ปี (ยอดนิยม)</b></div>
            <div class="cai-tld-badge">.ai <b>฿2,290/ปี</b></div>
            <div class="cai-tld-badge">.co.th <b>฿800/ปี</b></div>
        </div>
    </div>
</section>

<!-- 5. 4 Interactive Pillars of Success (สร้าง | เปิดตัว | เติบโต | จัดการ) -->
<section class="cai-workflow-sec">
    <div class="cai-section-header">
        <span class="cai-section-tag">COMPLETE DIGITAL JOURNEY</span>
        <h2 class="cai-section-title">เครื่องมือครบวงจรสำหรับทุกโปรเจกต์ออนไลน์</h2>
        <p class="cai-section-subtitle">ควบคุมและพัฒนาสิ่งที่สร้างบนโลกออนไลน์ได้อย่างเต็มที่ โดยไม่ต้องปวดหัวกับเรื่องเทคนิค</p>
    </div>

    <div class="cai-workflow-tabs">
        <button class="cai-workflow-tab-btn active" onclick="switchTab(0)">
            <i class="fas fa-magic"></i> 1. สร้าง (Build)
        </button>
        <button class="cai-workflow-tab-btn" onclick="switchTab(1)">
            <i class="fas fa-rocket"></i> 2. เปิดตัว (Launch)
        </button>
        <button class="cai-workflow-tab-btn" onclick="switchTab(2)">
            <i class="fas fa-chart-line"></i> 3. เติบโต (Grow)
        </button>
        <button class="cai-workflow-tab-btn" onclick="switchTab(3)">
            <i class="fas fa-sliders-h"></i> 4. จัดการ (Manage)
        </button>
    </div>

    <!-- Tab 1: สร้าง -->
    <div class="cai-workflow-panel active" id="tabPanel0">
        <div class="cai-wf-content">
            <span class="badge-card badge-popular mb-3 d-inline-block">สร้างสรรค์ด้วย AI เรียลไทม์</span>
            <h3>เพียงสั่งการด้วยข้อความ AI จะเนรมิตหน้าเว็บให้ทันที</h3>
            <p>
                อธิบายสิ่งที่คุณต้องการสร้าง แล้วปล่อยให้ตัวช่วย AI ช่วยเขียนโค้ด ออกแบบเลย์เอาต์ สร้างภาพประกอบ และร่างข้อความคอนเทนต์ที่ตรงใจคุณ ปรับแต่งได้ตามต้องการจนกว่าจะสมบูรณ์แบบ
            </p>
            <ul class="cai-wf-points">
                <li><i class="fas fa-check-circle"></i> ป้อนไอเดียและสร้างแผนผังเว็บไซต์พร้อมใช้ในไม่กี่คลิก</li>
                <li><i class="fas fa-check-circle"></i> เทมเพลตระดับมืออาชีพ รองรับมือถือและแท็บเล็ต 100%</li>
                <li><i class="fas fa-check-circle"></i> ติดตั้ง WordPress และปลั๊กอินยอดนิยมได้ใน 1 นาที</li>
            </ul>
            <a href="#pricing" class="cai-btn-primary">
                เริ่มสร้างเว็บไซต์เลย <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="cai-wf-preview-box">
            <div class="cai-wf-screen-header">
                <span class="wf-dot wf-dot-red"></span>
                <span class="wf-dot wf-dot-yellow"></span>
                <span class="wf-dot wf-dot-green"></span>
                <span style="font-size: 12px; color: #94a3b8; margin-left: 10px;">CreateAI Builder - Live Preview</span>
            </div>
            <div style="background: rgba(103, 61, 230, 0.1); border: 1px dashed rgba(168, 85, 247, 0.4); border-radius: 10px; padding: 25px; text-align: center;">
                <i class="fas fa-sparkles" style="font-size: 38px; color: #fdd700; margin-bottom: 15px;"></i>
                <div style="font-size: 16px; font-weight: 700; color: #ffffff; margin-bottom: 8px;">AI กำลังสร้างหน้าแรกของคุณ...</div>
                <div style="font-size: 13px; color: #94a3b8;">วิเคราะห์โครงสร้างธุรกิจ > วางระบบแบนเนอร์ > สร้างระบบตะกร้าสินค้า</div>
            </div>
        </div>
    </div>

    <!-- Tab 2: เปิดตัว -->
    <div class="cai-workflow-panel" id="tabPanel1">
        <div class="cai-wf-content">
            <span class="badge-card badge-standard mb-3 d-inline-block">เปิดตัวสู่โลกออนไลน์</span>
            <h3>ออนไลน์เว็บไซต์สู่สายตาคนทั่วโลกใน 60 วินาที</h3>
            <p>
                ระบบ Deploy อัตโนมัติเชื่อมต่อชื่อโดเมน ติดตั้งใบรับรองความปลอดภัย SSL ฟรีตลอดชีพ และกระจายข้อมูลผ่านเครือข่ายความเร็วสูง LiteSpeed Web Server เว็บโหลดเร็วติดสปีดทันทีตั้งแต่วันแรก
            </p>
            <ul class="cai-wf-points">
                <li><i class="fas fa-check-circle"></i> เซิร์ฟเวอร์ NVMe Gen4 อ่าน-เขียนเร็ว 7,450 MB/s</li>
                <li><i class="fas fa-check-circle"></i> ป้องกันการโจมตี DDoS Shield ระดับ 100 Gbps</li>
                <li><i class="fas fa-check-circle"></i> ฟรี ใบรับรอง SSL Certificate ไม่จำกัดจำนวนโดเมน</li>
            </ul>
            <a href="#pricing" class="cai-btn-primary">
                เลือกแพ็กเกจเปิดตัว <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="cai-wf-preview-box">
            <div class="cai-wf-screen-header">
                <span class="wf-dot wf-dot-red"></span>
                <span class="wf-dot wf-dot-yellow"></span>
                <span class="wf-dot wf-dot-green"></span>
                <span style="font-size: 12px; color: #94a3b8; margin-left: 10px;">Deployment Pipeline</span>
            </div>
            <div style="font-family: monospace; font-size: 13px; color: #34d399; line-height: 1.8;">
                <div>[✓] Domain DNS Propagation: Complete</div>
                <div>[✓] SSL Handshake Secured: 256-bit AES</div>
                <div>[✓] LiteSpeed LSCache: Enabled (5x Speed)</div>
                <div style="color: #fdd700;">[STATUS] Site is Live at: https://yourbrand.com</div>
            </div>
        </div>
    </div>

    <!-- Tab 3: เติบโต -->
    <div class="cai-workflow-panel" id="tabPanel2">
        <div class="cai-wf-content">
            <span class="badge-card badge-gpu mb-3 d-inline-block">ตัวแทน AI อัตโนมัติ</span>
            <h3>ส่งต่องานประจำวันให้ AI Agent ดูแลแทนคุณตลอด 24 ชม.</h3>
            <p>
                ขยายธุรกิจได้อย่างไม่สะดุดด้วยแอป OpenClaw และ Agentic AI ตอบคำถามลูกค้าผ่าน Telegram/Line รวบรวมข้อมูลคู่แข่ง สรุปยอดขาย และส่งอีเมลอัตโนมัติ ช่วยลดต้นทุนแรงงานและเพิ่มยอดขายอย่างต่อเนื่อง
            </p>
            <ul class="cai-wf-points">
                <li><i class="fas fa-check-circle"></i> ทำงานตลอด 24 ชั่วโมง 7 วัน ไม่เคยหลับ</li>
                <li><i class="fas fa-check-circle"></i> เชื่อมต่อ Telegram & Line Bot สั่งงานผ่านมือถือได้ทันที</li>
                <li><i class="fas fa-check-circle"></i> ท่องเว็บวิเคราะห์ข้อมูล และร่างอีเมลสื่อสารอย่างแม่นยำ</li>
            </ul>
            <a href="#openclaw" class="cai-btn-primary">
                ใช้งานตัวแทน AI ฿199/ด. <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="cai-wf-preview-box">
            <div class="cai-wf-screen-header">
                <span class="wf-dot wf-dot-red"></span>
                <span class="wf-dot wf-dot-yellow"></span>
                <span class="wf-dot wf-dot-green"></span>
                <span style="font-size: 12px; color: #94a3b8; margin-left: 10px;">OpenClaw Automation Log</span>
            </div>
            <div style="font-family: monospace; font-size: 12.5px; color: #e2e8f0; line-height: 1.7;">
                <div style="color: #94a3b8;">02:14:10 - Agent wake-up: Checking inbox...</div>
                <div style="color: #38bdf8;">02:14:15 - New inquiry from customer: Replied via Telegram</div>
                <div style="color: #34d399;">02:15:00 - Daily report generated & synced to Google Sheets</div>
                <div style="color: #fdd700;">02:15:20 - Automated task executed: 100% SUCCESS</div>
            </div>
        </div>
    </div>

    <!-- Tab 4: จัดการ -->
    <div class="cai-workflow-panel" id="tabPanel3">
        <div class="cai-wf-content">
            <span class="badge-card badge-vps mb-3 d-inline-block">แผงควบคุมศูนย์กลาง</span>
            <h3>บริหารจัดการทุกบริการได้จากหน้าจอเดียวที่เรียบง่าย</h3>
            <p>
                บอกลาแผงควบคุมที่ยุ่งเหยิง จัดการชื่อโดเมน ฐานข้อมูล อีเมลองค์กร พื้นที่จัดเก็บข้อมูล และเซิร์ฟเวอร์ VPS ได้ง่ายดายในที่เดียว พร้อมการสำรองข้อมูลอัตโนมัติ กู้คืนได้ทันทีเมื่อเกิดเหตุฉุกเฉิน
            </p>
            <ul class="cai-wf-points">
                <li><i class="fas fa-check-circle"></i> สำรองข้อมูลรายวันและรายสัปดาห์อัตโนมัติ กู้คืนใน 1 คลิก</li>
                <li><i class="fas fa-check-circle"></i> สร้างบัญชีอีเมลธุรกิจภายใต้ชื่อโดเมนของคุณได้ทันที</li>
                <li><i class="fas fa-check-circle"></i> เชื่อมต่อระบบสมาชิก WHMCS และใบเสร็จรับเงินอย่างเป็นระบบ</li>
            </ul>
            <a href="/billing/clientarea.php" class="cai-btn-primary">
                เข้าสู่แผงควบคุมสมาชิก <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <div class="cai-wf-preview-box">
            <div class="cai-wf-screen-header">
                <span class="wf-dot wf-dot-red"></span>
                <span class="wf-dot wf-dot-yellow"></span>
                <span class="wf-dot wf-dot-green"></span>
                <span style="font-size: 12px; color: #94a3b8; margin-left: 10px;">CreateAI Dashboard Overview</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between; background: rgba(255,255,255,0.04); padding: 8px 12px; border-radius: 6px;">
                    <span>Active Websites:</span> <strong style="color: #ffffff;">100 / 100 Active</strong>
                </div>
                <div style="display: flex; justify-content: space-between; background: rgba(255,255,255,0.04); padding: 8px 12px; border-radius: 6px;">
                    <span>Storage Usage:</span> <strong style="color: #34d399;">24.5 GB / 100 GB (NVMe)</strong>
                </div>
                <div style="display: flex; justify-content: space-between; background: rgba(255,255,255,0.04); padding: 8px 12px; border-radius: 6px;">
                    <span>SSL Certificate:</span> <strong style="color: #38bdf8;">Auto-Renew Active (Lifetime)</strong>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. OpenClaw AI Automation Spotlight Section -->
<section class="cai-openclaw-sec" id="openclaw">
    <div class="cai-openclaw-container">
        <div class="cai-openclaw-card">
            <div>
                <span class="cai-oc-badge"><i class="fas fa-robot"></i> ตัวแทน AI อัจฉริยะ (OPENCLAW)</span>
                <h2 class="cai-oc-title">
                    มีผู้ช่วย AI ทำงานแทนคุณ 24 ชั่วโมง <br>ออนไลน์พร้อมใช้ใน 60 วินาที
                </h2>
                <p class="cai-oc-desc">
                    ติดตั้งเพียงคลิกเดียว แล้วระบบจะขับเคลื่อนงานประจำวันของคุณตลอด 24 ชั่วโมง 7 วัน แม้ในเวลาที่คุณกำลังหลับ หมดกังวลเรื่องการตั้งค่าเซิร์ฟเวอร์หรือบำรุงรักษา เราดูแลระบบให้คุณทั้งหมด
                </p>

                <div class="cai-oc-feature-grid">
                    <div class="cai-oc-feat-item">
                        <i class="fas fa-bolt"></i>
                        <div>
                            <strong>พร้อมใช้งานทันที</strong>
                            <span>ไม่ต้องติดตั้งระบบ Linux หรือคำสั่งยุ่งยาก</span>
                        </div>
                    </div>
                    <div class="cai-oc-feat-item">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>ดูแลความปลอดภัยให้ 100%</strong>
                            <span>อัปเดตแพตช์และสำรองข้อมูลอัตโนมัติ</span>
                        </div>
                    </div>
                    <div class="cai-oc-feat-item">
                        <i class="fab fa-telegram-plane"></i>
                        <div>
                            <strong>เชื่อมต่อ Telegram ในตัว</strong>
                            <span>สั่งการและรับรายงานด่วนผ่านมือถือได้ทุกที่</span>
                        </div>
                    </div>
                    <div class="cai-oc-feat-item">
                        <i class="fas fa-envelope-open-text"></i>
                        <div>
                            <strong>อีเมล Agentic & ท่องเว็บ</strong>
                            <span>ค้นหาข้อมูลทางอินเทอร์เน็ตและตอบอีเมลอัตโนมัติ</span>
                        </div>
                    </div>
                    <div class="cai-oc-feat-item">
                        <i class="fas fa-coins"></i>
                        <div>
                            <strong>เครดิต AI ในตัว</strong>
                            <span>จัดสรรเครดิตล่วงหน้า พร้อมรองรับ API Key ของคุณ</span>
                        </div>
                    </div>
                    <div class="cai-oc-feat-item">
                        <i class="fas fa-terminal"></i>
                        <div>
                            <strong>มีอินเทอร์เฟซและ CLI</strong>
                            <span>ใช้งานได้ทั้งหน้าเว็บแบบเห็นภาพและคำสั่ง CLI</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Box -->
            <div class="cai-oc-pricing-box">
                <span class="cai-oc-discount-pill">🔥 ส่วนลดพิเศษ 72%</span>
                <div style="font-size: 18px; font-weight: 700; color: #ffffff; margin-bottom: 8px;">แอป AI Automation</div>
                <div class="strike-price" style="font-size: 16px; margin-bottom: 4px;">฿699</div>
                <div class="cai-oc-price-row">
                    <span class="cai-oc-currency">฿</span>
                    <span class="cai-oc-num">199</span>
                    <span class="cai-oc-period">/เดือน</span>
                </div>
                <div class="cai-oc-renew">ต่ออายุที่ ฿349/เดือน เป็นเวลา 2 ปี | ยกเลิกได้ตลอดเวลา</div>
                <a href="/billing/cart.php?a=add&pid=1" class="cai-oc-btn">
                    เริ่มใช้งาน OpenClaw ตอนนี้ <i class="fas fa-arrow-right ms-2"></i>
                </a>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 14px;">
                    <i class="fas fa-check text-success me-1"></i> รับประกันความพึงพอใจ 30 วัน
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. 4-Column Aligned Pricing Matrix -->
<section class="cai-pricing-sec" id="pricing">
    <div class="cai-section-header">
        <span class="cai-section-tag">POPULAR PRICING PLANS</span>
        <h2 class="cai-section-title">เลือกแพ็กเกจที่ตรงกับเป้าหมายของคุณ</h2>
        <p class="cai-section-subtitle">บริการคลาวด์เว็บโฮสติ้งความเร็วสูง คำนวณราคาโปร่งใส ไม่มีค่าบริการแอบแฝง จัดการได้ง่ายในแผงเดียว</p>
    </div>

    <div class="cai-pricing-grid-4">
        <!-- Card 1: Single Web Hosting -->
        <div class="cai-price-card">
            <div class="card-row-badge">
                <span class="badge-card badge-standard">เริ่มต้นเบาๆ</span>
            </div>
            <div class="card-row-title">Single Web Hosting</div>
            <div class="card-row-desc">เหมาะสำหรับ 1 เว็บไซต์ บล็อกส่วนตัว หรือเริ่มทำเว็บ</div>
            <div class="card-row-price">
                <span class="price-currency">฿</span>
                <span class="price-number">29</span>
                <span class="price-period">/เดือน</span>
            </div>
            <div class="card-row-save">
                <span class="save-pill">ประหยัด 75%</span>
                <span class="strike-price">฿109</span>
            </div>
            <div class="card-row-btn">
                <a href="/billing/cart.php?a=add&pid=8" class="card-btn card-btn-default">
                    เลือกแพ็กเกจนี้
                </a>
            </div>
            <div class="card-divider"></div>
            <ul class="card-feature-list">
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> <b>1 เว็บไซต์</b> ใช้งานได้อิสระ</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> <b>50GB</b> NVMe SSD พื้นที่จัดเก็บ</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> <b>100GB</b> แบนด์วิดท์รายเดือน</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> ฟรี <b>SSL Certificate</b> ตลอดชีพ</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> 1 บัญชีอีเมลธุรกิจ (@domain)</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> 1-Click ติดตั้ง WordPress</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> สำรองข้อมูลรายสัปดาห์</li>
            </ul>
        </div>

        <!-- Card 2: Premium Web Hosting (FEATURED) -->
        <div class="cai-price-card featured">
            <div class="card-row-badge">
                <span class="badge-card badge-popular"><i class="fas fa-fire me-1"></i> ยอดนิยมอันดับ 1</span>
            </div>
            <div class="card-row-title">Premium Web Hosting</div>
            <div class="card-row-desc">คุ้มค่าที่สุด สำหรับธุรกิจ เว็บไซต์บริษัท และร้านค้าออนไลน์</div>
            <div class="card-row-price">
                <span class="price-currency">฿</span>
                <span class="price-number" style="color: #fdd700;">59</span>
                <span class="price-period">/เดือน</span>
            </div>
            <div class="card-row-save">
                <span class="save-pill" style="background: rgba(238, 85, 134, 0.2); color: #ee5586;">ประหยัด 75% + ฟรีโดเมน 1 ปี</span>
                <span class="strike-price">฿159</span>
            </div>
            <div class="card-row-btn">
                <a href="/billing/cart.php?a=add&pid=2" class="card-btn card-btn-featured">
                    เลือกแพ็กเกจยอดนิยม <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            <div class="card-divider"></div>
            <ul class="card-feature-list">
                <li class="card-feature-item"><i class="fas fa-check-circle" style="color: #fdd700;"></i> <b>100 เว็บไซต์</b> ไม่จำกัดการเติบโต</li>
                <li class="card-feature-item"><i class="fas fa-check-circle" style="color: #fdd700;"></i> <b>100GB</b> NVMe SSD สปีดสูงพิเศษ</li>
                <li class="card-feature-item"><i class="fas fa-check-circle" style="color: #fdd700;"></i> <b>แบนด์วิดท์ไม่จำกัด</b> (Unlimited)</li>
                <li class="card-feature-item"><i class="fas fa-check-circle" style="color: #fdd700;"></i> <b>ฟรี โดเมนเนม 1 ปีเต็ม</b> (.com)</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> ฟรี SSL ไม่จำกัดทุกโดเมน</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> 100 บัญชีอีเมลธุรกิจ</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> LiteSpeed Cache เร่งสปีด 5x</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> สำรองข้อมูลอัตโนมัติรายสัปดาห์</li>
            </ul>
        </div>

        <!-- Card 3: Business Web Hosting -->
        <div class="cai-price-card">
            <div class="card-row-badge">
                <span class="badge-card badge-vps">⚡ สำหรับธุรกิจ & ร้านค้า</span>
            </div>
            <div class="card-row-title">Business Web Hosting</div>
            <div class="card-row-desc">สปีดไวพิเศษ พร้อมสำรองข้อมูลรายวันและระบบความปลอดภัยสูง</div>
            <div class="card-row-price">
                <span class="price-currency">฿</span>
                <span class="price-number">89</span>
                <span class="price-period">/เดือน</span>
            </div>
            <div class="card-row-save">
                <span class="save-pill">ประหยัด 70% + ฟรีโดเมน</span>
                <span class="strike-price">฿229</span>
            </div>
            <div class="card-row-btn">
                <a href="/billing/cart.php?a=add&pid=3" class="card-btn card-btn-default">
                    เลือกแพ็กเกจธุรกิจ
                </a>
            </div>
            <div class="card-divider"></div>
            <ul class="card-feature-list">
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> <b>100 เว็บไซต์</b> รองรับทราฟฟิกสูง</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> <b>200GB</b> NVMe SSD พื้นที่จุใจ</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> แบนด์วิดท์ไม่จำกัด (Unlimited)</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> <b>ฟรี โดเมนเนม 1 ปีเต็ม</b></li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> <b>สำรองข้อมูลอัตโนมัติรายวัน (Daily)</b></li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> ฟรี Cloudflare CDN เร่งความเร็ว</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> เครดิตเครื่องมือ AI ในตัว</li>
            </ul>
        </div>

        <!-- Card 4: Cloud Startup -->
        <div class="cai-price-card">
            <div class="card-row-badge">
                <span class="badge-card badge-gpu">☁️ คลาวด์ทรงพลัง</span>
            </div>
            <div class="card-row-title">Cloud Startup</div>
            <div class="card-row-desc">ทรัพยากร Dedicated แท้ รองรับโปรเจกต์ขนาดใหญ่</div>
            <div class="card-row-price">
                <span class="price-currency">฿</span>
                <span class="price-number">209</span>
                <span class="price-period">/เดือน</span>
            </div>
            <div class="card-row-save">
                <span class="save-pill">ประหยัด 65% คลาวด์แท้</span>
                <span class="strike-price">฿559</span>
            </div>
            <div class="card-row-btn">
                <a href="/billing/cart.php?a=add&pid=4" class="card-btn card-btn-default">
                    สั่งซื้อ Cloud Startup
                </a>
            </div>
            <div class="card-divider"></div>
            <ul class="card-feature-list">
                <li class="card-feature-item"><i class="fas fa-check-circle" style="color: #38bdf8;"></i> <b>300 เว็บไซต์</b> ขยายได้อย่างไร้ขีดจำกัด</li>
                <li class="card-feature-item"><i class="fas fa-check-circle" style="color: #38bdf8;"></i> <b>200GB</b> NVMe Storage</li>
                <li class="card-feature-item"><i class="fas fa-check-circle" style="color: #38bdf8;"></i> <b>2 vCPU Cores & 3GB RAM Dedicated</b></li>
                <li class="card-feature-item"><i class="fas fa-check-circle" style="color: #38bdf8;"></i> <b>1 Dedicated IPv4</b> ส่วนตัว</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> ฟรี โดเมนเนม 1 ปีเต็ม</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> โครงสร้าง Isolated Cloud 100%</li>
                <li class="card-feature-item"><i class="fas fa-check-circle"></i> ซัพพอร์ตระดับ VIP Priority</li>
            </ul>
        </div>
    </div>
</section>

<!-- 8. Interactive Cloud VPS Slider -->
<section class="cai-slider-sec">
    <div class="cai-slider-box">
        <div style="text-align: center; margin-bottom: 30px;">
            <span class="cai-section-tag">FLEXIBLE VPS COMPUTING</span>
            <h3 style="font-size: 28px; font-weight: 800; color: #ffffff; margin-bottom: 8px;">ปรับแต่งขนาด KVM Cloud VPS ได้ตามใจคุณ</h3>
            <p style="color: #94a3b8; font-size: 14.5px;">เลื่อนสไลด์เพื่อเลือกสเปก vCPU, RAM และพื้นที่ NVMe SSD ที่เหมาะกับการใช้งานของคุณ</p>
        </div>

        <div class="cai-slider-steps">
            <div class="cai-step-marker" onclick="setVpsStep(0)"><div class="dot-marker"></div>KVM 1 (฿199)</div>
            <div class="cai-step-marker active" onclick="setVpsStep(1)"><div class="dot-marker"></div>KVM 2 (฿299)</div>
            <div class="cai-step-marker" onclick="setVpsStep(2)"><div class="dot-marker"></div>KVM 4 (฿549)</div>
            <div class="cai-step-marker" onclick="setVpsStep(3)"><div class="dot-marker"></div>KVM 8 (฿1,099)</div>
        </div>

        <input type="range" min="0" max="3" value="1" step="1" id="vpsRangeSlider" class="cai-range-bar" oninput="updateVpsFromSlider(this.value)">

        <div class="cai-slider-result-grid">
            <div class="cai-slider-spec-col">
                <div class="cai-spec-number" id="specCpu">2 Cores</div>
                <div class="cai-spec-label">vCPU Dedicated</div>
            </div>
            <div class="cai-slider-spec-col">
                <div class="cai-spec-number" id="specRam">8 GB</div>
                <div class="cai-spec-label">RAM Memory</div>
            </div>
            <div class="cai-slider-spec-col">
                <div class="cai-spec-number" id="specDisk">100 GB</div>
                <div class="cai-spec-label">NVMe Gen4 SSD</div>
            </div>
            <div class="cai-slider-spec-col">
                <div class="cai-spec-number" id="specBw">8 TB</div>
                <div class="cai-spec-label">Bandwidth (1Gbps)</div>
            </div>
            <div class="cai-slider-buy-col">
                <div class="cai-slider-price-now" id="specPrice">฿299 <span style="font-size: 14px; color: #cbd5e1; font-weight: 400;">/ด.</span></div>
                <a href="/billing/cart.php?a=add&pid=6" id="specBtn" class="cai-btn-primary" style="padding: 9px 18px; font-size: 13.5px; width: 100%; justify-content: center;">
                    สั่งซื้อแพ็กเกจนี้ <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 9. GPU AI Cloud On-Demand Showcase -->
<section class="cai-gpu-sec">
    <div class="cai-section-header">
        <span class="cai-section-tag" style="color: #ee5586;">ON-DEMAND AI ACCELERATORS</span>
        <h2 class="cai-section-title">ขุมพลัง GPU Cloud สำหรับเทรนและรัน AI</h2>
        <p class="cai-section-subtitle">บริการคลาวด์ GPU รายชั่วโมงเชื่อมต่อ Vast.ai สั่งเปิดเครื่อง เชื่อมต่อ SSH หรือ JupyterLab และรัน vLLM / ComfyUI ได้ทันทีใน 60 วินาที</p>
    </div>

    <div class="cai-gpu-grid-3">
        <!-- GPU 1: RTX 4090 -->
        <div class="cai-gpu-card">
            <div class="cai-gpu-top">
                <div class="cai-gpu-model">GeForce RTX 4090</div>
                <div class="cai-gpu-vram-tag">24GB GDDR6X</div>
            </div>
            <div class="cai-gpu-price-hourly">
                ~฿11.50 <span>/ชม. ($0.32/hr)</span>
            </div>
            <p style="font-size: 13px; color: #94a3b8; margin-bottom: 18px;">
                ตัวเลือกยอดนิยมสำหรับ AI Developer รันโมเดลภาษา 8B-14B หรือเจนภาพ Stable Diffusion / ComfyUI สปีดสูง
            </p>
            <ul class="cai-gpu-features">
                <li><i class="fas fa-check"></i> 16,384 CUDA Cores</li>
                <li><i class="fas fa-check"></i> vLLM Ready (Llama-3.1, Qwen-2.5)</li>
                <li><i class="fas fa-check"></i> เชื่อมต่อ SSH & JupyterLab ใน 1 คลิก</li>
                <li><i class="fas fa-check"></i> ปิดเครื่องหยุดคิดเงินทันที</li>
            </ul>
            <a href="/billing/gpu_cloud.php" class="cai-btn-secondary" style="justify-content: center; padding: 10px 0;">
                ดูข้อเสนอ RTX 4090
            </a>
        </div>

        <!-- GPU 2: RTX 5090 -->
        <div class="cai-gpu-card" style="border-color: #a855f7; background: linear-gradient(180deg, #1d1736 0%, #131427 100%);">
            <div class="cai-gpu-top">
                <div class="cai-gpu-model">GeForce RTX 5090 <span class="cai-nav-pill-badge badge-hot ms-1">NEW</span></div>
                <div class="cai-gpu-vram-tag" style="background: rgba(238, 85, 134, 0.2); color: #ee5586;">32GB GDDR7</div>
            </div>
            <div class="cai-gpu-price-hourly" style="color: #fdd700;">
                ~฿23.50 <span>/ชม. ($0.65/hr)</span>
            </div>
            <p style="font-size: 13px; color: #94a3b8; margin-bottom: 18px;">
                สถาปัตยกรรม Blackwell ล่าสุด แบนด์วิดท์ทะลุ 1.7 TB/s เหมาะกับ DeepSeek-R1 และโมเดลรุ่นใหม่
            </p>
            <ul class="cai-gpu-features">
                <li><i class="fas fa-check" style="color: #fdd700;"></i> สถาปัตยกรรม Blackwell รุ่นใหม่ล่าสุด</li>
                <li><i class="fas fa-check" style="color: #fdd700;"></i> 21,760 CUDA Cores + Tensor Gen 5</li>
                <li><i class="fas fa-check" style="color: #fdd700;"></i> รัน Qwen 2.5 32B AWQ สบายๆ</li>
                <li><i class="fas fa-check" style="color: #fdd700;"></i> คิดเงินตามจริงเป็นวินาที</li>
            </ul>
            <a href="/billing/gpu_cloud.php" class="cai-btn-primary" style="justify-content: center; padding: 10px 0;">
                เปิดใช้งาน RTX 5090
            </a>
        </div>

        <!-- GPU 3: NVIDIA A100 -->
        <div class="cai-gpu-card">
            <div class="cai-gpu-top">
                <div class="cai-gpu-model">NVIDIA A100 SXM4</div>
                <div class="cai-gpu-vram-tag">80GB HBM2e</div>
            </div>
            <div class="cai-gpu-price-hourly">
                ~฿39.50 <span>/ชม. ($1.10/hr)</span>
            </div>
            <p style="font-size: 13px; color: #94a3b8; margin-bottom: 18px;">
                ระดับ Enterprise สำหรับงานเทรนโมเดล, Fine-tuning และ Large Scale LLM Inference ระดับสูง
            </p>
            <ul class="cai-gpu-features">
                <li><i class="fas fa-check"></i> 80GB HBM2e High Speed Memory</li>
                <li><i class="fas fa-check"></i> รัน Llama-3-70B & DeepSeek ขนาดใหญ่</li>
                <li><i class="fas fa-check"></i> NVLink 600 GB/s สปีดไวระดับเซิร์ฟเวอร์</li>
                <li><i class="fas fa-check"></i> ใช้งานแบบ On-Demand หรือรายเดือน</li>
            </ul>
            <a href="/billing/gpu_cloud.php" class="cai-btn-secondary" style="justify-content: center; padding: 10px 0;">
                ดูข้อเสนอ A100
            </a>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="/billing/gpu_cloud.php" class="cai-btn-primary cai-btn-large">
            <i class="fas fa-desktop me-2"></i> เข้าสู่หน้าจัดการ GPU Cloud Portal (Vast.ai)
        </a>
    </div>
</section>

<!-- 10. Detailed Feature Comparison Table -->
<section class="cai-compare-sec">
    <div class="cai-section-header">
        <span class="cai-section-tag">DETAILED FEATURE COMPARISON</span>
        <h2 class="cai-section-title">ตารางเปรียบเทียบคุณสมบัติทุกแพ็กเกจ</h2>
        <p class="cai-section-subtitle">เปรียบเทียบฟังก์ชันและความคุ้มค่าแบบชัดเจน เพื่อให้คุณเลือกบริการที่ตรงใจที่สุด</p>
    </div>

    <div class="cai-compare-wrapper">
        <table class="cai-compare-table">
            <thead>
                <tr>
                    <th>คุณสมบัติหลัก</th>
                    <th>Single Web (฿29)</th>
                    <th class="col-highlight">Premium Web (฿59)</th>
                    <th>Business Web (฿89)</th>
                    <th>Cloud Startup (฿209)</th>
                    <th>OpenClaw AI (฿199)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>จำนวนเว็บไซต์ที่รองรับ</td>
                    <td>1 เว็บไซต์</td>
                    <td class="col-highlight">100 เว็บไซต์</td>
                    <td>100 เว็บไซต์</td>
                    <td>300 เว็บไซต์</td>
                    <td>บอท & เอเจนต์ 24/7</td>
                </tr>
                <tr>
                    <td>พื้นที่จัดเก็บข้อมูล (Storage)</td>
                    <td>50 GB SSD</td>
                    <td class="col-highlight">100 GB NVMe</td>
                    <td>200 GB NVMe</td>
                    <td>200 GB NVMe</td>
                    <td>พื้นที่คลาวด์ส่วนตัว</td>
                </tr>
                <tr>
                    <td>หน่วยความจำ / ทรัพยากร</td>
                    <td>1 GB RAM</td>
                    <td class="col-highlight">2 GB RAM</td>
                    <td>3 GB RAM</td>
                    <td>2 Cores / 3GB Dedicated</td>
                    <td>คลาวด์โปรเซสเซอร์ AI</td>
                </tr>
                <tr>
                    <td>ฟรี โดเมนเนม 1 ปีเต็ม</td>
                    <td><i class="fas fa-minus icon-minus"></i></td>
                    <td class="col-highlight"><i class="fas fa-check-circle icon-check"></i> ฟรี 1 ปี (.com/.site)</td>
                    <td><i class="fas fa-check-circle icon-check"></i> ฟรี 1 ปี (.com/.site)</td>
                    <td><i class="fas fa-check-circle icon-check"></i> ฟรี 1 ปี</td>
                    <td><i class="fas fa-minus icon-minus"></i></td>
                </tr>
                <tr>
                    <td>ฟรี ใบรับรอง SSL Certificate</td>
                    <td><i class="fas fa-check-circle icon-check"></i> ตลอดชีพ</td>
                    <td class="col-highlight"><i class="fas fa-check-circle icon-check"></i> ไม่จำกัด</td>
                    <td><i class="fas fa-check-circle icon-check"></i> ไม่จำกัด</td>
                    <td><i class="fas fa-check-circle icon-check"></i> ไม่จำกัด</td>
                    <td><i class="fas fa-check-circle icon-check"></i> มีในตัว</td>
                </tr>
                <tr>
                    <td>LiteSpeed Cache เร่งสปีด 5x</td>
                    <td><i class="fas fa-check-circle icon-check"></i></td>
                    <td class="col-highlight"><i class="fas fa-check-circle icon-check"></i></td>
                    <td><i class="fas fa-check-circle icon-check"></i></td>
                    <td><i class="fas fa-check-circle icon-check"></i></td>
                    <td><i class="fas fa-minus icon-minus"></i></td>
                </tr>
                <tr>
                    <td>การสำรองข้อมูล (Backup)</td>
                    <td>รายสัปดาห์</td>
                    <td class="col-highlight">รายสัปดาห์</td>
                    <td><b style="color: #34d399;">รายวัน (Daily)</b></td>
                    <td><b style="color: #34d399;">รายวัน (Daily)</b></td>
                    <td>คลาวด์แบ็กอัปอัตโนมัติ</td>
                </tr>
                <tr>
                    <td>Dedicated IP ส่วนตัว</td>
                    <td><i class="fas fa-minus icon-minus"></i></td>
                    <td class="col-highlight"><i class="fas fa-minus icon-minus"></i></td>
                    <td><i class="fas fa-minus icon-minus"></i></td>
                    <td><i class="fas fa-check-circle icon-check"></i> 1 Dedicated IP</td>
                    <td><i class="fas fa-minus icon-minus"></i></td>
                </tr>
                <tr>
                    <td>ตัวแทน AI Automation</td>
                    <td><i class="fas fa-minus icon-minus"></i></td>
                    <td class="col-highlight"><i class="fas fa-minus icon-minus"></i></td>
                    <td>เครดิต AI เว็บไซต์</td>
                    <td>เครดิต AI เว็บไซต์</td>
                    <td><b style="color: #fdd700;">OpenClaw AI เต็มรูปแบบ</b></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- 11. Why Creators & Businesses Choose Us -->
<section class="cai-why-sec" id="why-choose">
    <div class="cai-section-header">
        <span class="cai-section-tag">WHY CREATEAI CLOUD</span>
        <h2 class="cai-section-title">เหตุผลที่ลูกค้าและผู้สร้างธุรกิจเลือกเรา</h2>
        <p class="cai-section-subtitle">เรานำเทคโนโลยีโครงสร้างพื้นฐานระดับชั้นนำมาผสานเข้ากับ AI เพื่อมอบประสบการณ์ที่ดีที่สุด</p>
    </div>

    <div class="cai-why-grid">
        <div class="cai-why-card">
            <div class="cai-why-icon"><i class="fas fa-bolt"></i></div>
            <h3 class="cai-why-title">ความเร็วสูงพิเศษด้วย LiteSpeed</h3>
            <p class="cai-why-desc">
                เซิร์ฟเวอร์ LiteSpeed Enterprise ผสานไดรฟ์ NVMe Gen4 ช่วยให้หน้าเว็บโหลดไวกว่าโฮสติ้งทั่วไปถึง 5 เท่า เพิ่มคะแนน Google Core Web Vitals ทันที
            </p>
        </div>

        <div class="cai-why-card">
            <div class="cai-why-icon"><i class="fas fa-exchange-alt"></i></div>
            <h3 class="cai-why-title">ย้ายเว็บไซต์ฟรี 100% ไร้รอยต่อ</h3>
            <p class="cai-why-desc">
                มีเว็บไซต์อยู่ที่ผู้ให้บริการอื่น? ทีมผู้เชี่ยวชาญของเราพร้อมดำเนินการย้ายไฟล์ ฐานข้อมูล และอีเมลมาให้คุณฟรี โดยที่เว็บไซต์ยังเปิดบริการได้ตามปกติ
            </p>
        </div>

        <div class="cai-why-card">
            <div class="cai-why-icon"><i class="fas fa-robot"></i></div>
            <h3 class="cai-why-title">เครื่องมือ AI ช่วยเหลือในตัว</h3>
            <p class="cai-why-desc">
                ตั้งแต่การวางแผนโครงสร้างเว็บ สร้างบทความคอนเทนต์ ไปจนถึงตัวแทนอัตโนมัติ OpenClaw ที่ช่วยตอบลูกค้าผ่าน Telegram ตลอด 24 ชั่วโมง
            </p>
        </div>

        <div class="cai-why-card">
            <div class="cai-why-icon"><i class="fas fa-shield-virus"></i></div>
            <h3 class="cai-why-title">ความปลอดภัยระดับองค์กร</h3>
            <p class="cai-why-desc">
                ปกป้องเว็บไซต์จากการโจมตีทางอินเทอร์เน็ตด้วย Cloudflare DDoS Shield, ระบบไฟร์วอลล์ WAF และการสแกนมัลแวร์อัตโนมัติแบบเรียลไทม์
            </p>
        </div>

        <div class="cai-why-card">
            <div class="cai-why-icon"><i class="fas fa-clock"></i></div>
            <h3 class="cai-why-title">การันตีความเสถียร 99.9% Uptime</h3>
            <p class="cai-why-desc">
                มั่นใจได้ว่าธุรกิจของคุณจะเปิดให้บริการได้ตลอดเวลา ด้วยโครงสร้างศูนย์ข้อมูล Datacenter มาตรฐานสากล มีระบบไฟและเครือข่ายสำรองคู่ขนาน
            </p>
        </div>

        <div class="cai-why-card">
            <div class="cai-why-icon"><i class="fas fa-hand-holding-usd"></i></div>
            <h3 class="cai-why-title">รับประกันคืนเงินใน 30 วัน</h3>
            <p class="cai-why-desc">
                ทดลองใช้งานได้อย่างสบายใจไร้ความเสี่ยง หากบริการของเราไม่ตอบโจทย์ความต้องการของคุณ สามารถขอยกเลิกและรับเงินคืนเต็มจำนวนได้ทันที
            </p>
        </div>
    </div>
</section>

<!-- 12. News & Announcements Section -->
<section class="cai-news-sec" id="news">
    <div class="cai-section-header">
        <span class="cai-section-tag">LATEST ANNOUNCEMENTS</span>
        <h2 class="cai-section-title">ข่าวสาร & ประกาศอัปเดตระบบ</h2>
        <p class="cai-section-subtitle">ติดตามความคืบหน้า ข่าวสารเซิร์ฟเวอร์ และฟีเจอร์ใหม่จากทีมงาน CreateAI</p>
    </div>

    <div class="cai-news-grid">
        <?php
        $latest_posts = get_posts(array(
            'numberposts' => 3,
            'post_status' => 'publish'
        ));

        if (!empty($latest_posts)):
            foreach ($latest_posts as $post):
                setup_postdata($post);
        ?>
            <a href="<?php echo get_permalink($post->ID); ?>" class="cai-news-card">
                <div class="cai-news-meta">
                    <span class="cai-news-tag">ข่าวสาร</span>
                    <span><?php echo get_the_date('j F Y', $post->ID); ?></span>
                </div>
                <h3 class="cai-news-title"><?php echo esc_html($post->post_title); ?></h3>
                <p class="cai-news-desc">
                    <?php echo wp_trim_words($post->post_content, 22, '...'); ?>
                </p>
                <div class="cai-news-more">
                    อ่านรายละเอียด <i class="fas fa-arrow-right"></i>
                </div>
            </a>
        <?php
            endforeach;
            wp_reset_postdata();
        else:
        ?>
            <div class="cai-news-card">
                <div class="cai-news-meta">
                    <span class="cai-news-tag">ระบบ</span>
                    <span>16 ตุลาคม 2026</span>
                </div>
                <h3 class="cai-news-title">เปิดตัวบริการใหม่: แอป AI Automation (OpenClaw)</h3>
                <p class="cai-news-desc">พร้อมใช้งานตัวแทน AI อัจฉริยะ ทำงานแทนคุณตลอด 24 ชั่วโมง เชื่อมต่อ Telegram ในตัว ราคาเริ่มต้น ฿199/ด.</p>
                <div class="cai-news-more">อ่านต่อ <i class="fas fa-arrow-right"></i></div>
            </div>
            <div class="cai-news-card">
                <div class="cai-news-meta">
                    <span class="cai-news-tag">เครือข่าย</span>
                    <span>14 ตุลาคม 2026</span>
                </div>
                <h3 class="cai-news-title">อัปเกรดเซิร์ฟเวอร์ NVMe Gen4 สปีดไวขึ้น 300%</h3>
                <p class="cai-news-desc">ปรับปรุงโครงสร้างเครือข่ายความเร็วสูงเพื่อการโหลดหน้าเว็บและจัดการฐานข้อมูลที่รวดเร็วที่สุด</p>
                <div class="cai-news-more">อ่านต่อ <i class="fas fa-arrow-right"></i></div>
            </div>
            <div class="cai-news-card">
                <div class="cai-news-meta">
                    <span class="cai-news-tag">โปรโมชั่น</span>
                    <span>12 ตุลาคม 2026</span>
                </div>
                <h3 class="cai-news-title">โปรโมชั่นเปิดตัว: ลดสูงสุด 75% + ฟรีโดเมน 1 ปี</h3>
                <p class="cai-news-desc">สมัครใช้งานโฮสติ้งรายปีวันนี้ รับสิทธิ์จดโดเมนเนม .com หรือ .site ฟรี 1 ปีเต็ม</p>
                <div class="cai-news-more">อ่านต่อ <i class="fas fa-arrow-right"></i></div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- 13. FAQ Section -->
<section class="cai-faq-sec">
    <div class="cai-section-header">
        <span class="cai-section-tag">FREQUENTLY ASKED QUESTIONS</span>
        <h2 class="cai-section-title">คำถามที่พบบ่อย (FAQ)</h2>
        <p class="cai-section-subtitle">รวมคำตอบสำหรับข้อสงสัยที่พบบ่อยเกี่ยวกับการใช้งานโฮสติ้ง ตัวแทน AI และคลาวด์เซิร์ฟเวอร์</p>
    </div>

    <div class="cai-faq-wrap">
        <div class="cai-faq-item active">
            <div class="cai-faq-question" onclick="toggleFaq(this)">
                <span>สมัครใช้งานแล้วเว็บไซต์จะเปิดใช้งานได้ภายในกี่นาที?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="cai-faq-answer">
                ระบบของเราใช้การสร้างบัญชีอัตโนมัติ 100% (Instant Activation) ทันทีที่คุณชำระเงินเรียบร้อยผ่านระบบสมาชิก WHMCS คุณจะได้รับอีเมลข้อมูลการเข้าสู่ระบบ DirectAdmin / cPanel / คอนโซล VPS และสามารถเริ่มสร้างเว็บไซต์หรือติดตั้ง WordPress ได้ใน 60 วินาที
            </div>
        </div>

        <div class="cai-faq-item">
            <div class="cai-faq-question" onclick="toggleFaq(this)">
                <span>แพ็กเกจที่ระบุว่า "ฟรีโดเมน 1 ปี" มีเงื่อนไขอย่างไรบ้าง?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="cai-faq-answer">
                เมื่อคุณเลือกสมัครแพ็กเกจโฮสติ้งระยะเวลา 1 ปีขึ้นไป (ในแพ็กเกจ Premium, Business หรือ Cloud Startup) ระบบจะมอบสิทธิ์ในการจดทะเบียนชื่อโดเมนเนมยอดนิยม เช่น .com, .site, .online ฟรี 1 ปีเต็มทันทีในขั้นตอนการสั่งซื้อ
            </div>
        </div>

        <div class="cai-faq-item">
            <div class="cai-faq-question" onclick="toggleFaq(this)">
                <span>แอป AI Automation (OpenClaw) ช่วยทำงานอะไรได้บ้าง ต้องมีความรู้ด้านไอทีไหม?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="cai-faq-answer">
                ไม่จำเป็นต้องมีความรู้ด้านเทคนิคเลยครับ OpenClaw ถูกออกแบบมาให้เป็น "พนักงาน AI ส่วนตัว" ที่พร้อมใช้งานได้ทันที มีหน้าต่างควบคุมและสามารถเชื่อมต่อเข้ากับ Telegram ของคุณ เพื่อช่วยตอบคำถามลูกค้า รวบรวมข้อมูลจากเว็บไซต์ สรุปรายงาน และส่งอีเมลติดตามงานได้ตลอด 24 ชั่วโมง
            </div>
        </div>

        <div class="cai-faq-item">
            <div class="cai-faq-question" onclick="toggleFaq(this)">
                <span>ถ้ามีเว็บไซต์อยู่ที่ผู้ให้บริการอื่น ต้องการย้ายมา CreateAI มีค่าใช้จ่ายไหม?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="cai-faq-answer">
                ฟรี 100% ครับ ทีมงานของเรามีบริการช่วยย้ายไฟล์ ฐานข้อมูล และอีเมลจากผู้ให้บริการเดิมของคุณมายัง CreateAI อย่างปลอดภัย ข้อมูลไม่สูญหาย และเว็บไซต์ยังคงเปิดให้บริการได้ต่อเนื่องตลอดการย้าย เพียงเปิดคำร้องผ่านระบบ Support Ticket
            </div>
        </div>

        <div class="cai-faq-item">
            <div class="cai-faq-question" onclick="toggleFaq(this)">
                <span>นโยบายการรับประกันคืนเงินภายใน 30 วันทำงานอย่างไร?</span>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="cai-faq-answer">
                หากคุณทดลองใช้งานโฮสติ้งของเราแล้วรู้สึกว่าไม่ตรงกับความต้องการ คุณสามารถแจ้งขอยกเลิกและรับเงินคืนได้เต็มจำนวน 100% ภายใน 30 วันนับจากวันที่สั่งซื้อ โดยไม่มีการถามคำถามจุกจิก เพื่อให้คุณมั่นใจในคุณภาพการบริการของเราอย่างสูงสุด
            </div>
        </div>
    </div>
</section>

<!-- 14. Modern Footer -->
<footer class="cai-footer">
    <div class="cai-footer-inner">
        <div class="cai-footer-col">
            <a href="/" class="d-inline-block mb-3">
                <img src="/antler/assets/img/logo.svg" alt="CreateAI Cloud" width="180" height="45">
            </a>
            <p style="line-height: 1.6; margin-bottom: 20px;">
                CreateAI ผู้นำแพลตฟอร์ม Agentic AI, คลาวด์เว็บโฮสติ้ง KVM VPS สปีดสูง และ GPU AI Cloud On-Demand ระดับโลก มอบความเร็ว ความปลอดภัย และประสิทธิภาพสูงสุดให้ธุรกิจของคุณ
            </p>
            <div style="display: flex; gap: 12px; font-size: 16px;">
                <a href="#" style="color: #cbd5e1;"><i class="fab fa-facebook"></i></a>
                <a href="#" style="color: #cbd5e1;"><i class="fab fa-twitter"></i></a>
                <a href="#" style="color: #cbd5e1;"><i class="fab fa-github"></i></a>
                <a href="#" style="color: #cbd5e1;"><i class="fab fa-discord"></i></a>
            </div>
        </div>

        <div class="cai-footer-col">
            <h4>บริการโฮสติ้ง & คลาวด์</h4>
            <ul class="cai-footer-links">
                <li><a href="/billing/cart.php?a=add&pid=8">Single Web Hosting (฿29/ด.)</a></li>
                <li><a href="/billing/cart.php?a=add&pid=2">Premium Web Hosting (฿59/ด.)</a></li>
                <li><a href="/billing/cart.php?a=add&pid=3">Business Web Hosting (฿89/ด.)</a></li>
                <li><a href="/billing/cart.php?a=add&pid=4">Cloud Startup (฿209/ด.)</a></li>
                <li><a href="/billing/cart.php?a=add&pid=5">KVM 1 VPS (฿199/ด.)</a></li>
                <li><a href="/billing/cart.php?a=add&pid=6">KVM 2 Cloud VPS (฿299/ด.)</a></li>
                <li><a href="/billing/cart.php?a=add&pid=7">KVM 4 Cloud VPS (฿549/ด.)</a></li>
            </ul>
        </div>

        <div class="cai-footer-col">
            <h4>ตัวแทน AI & เครื่องมือ</h4>
            <ul class="cai-footer-links">
                <li><a href="/billing/cart.php?a=add&pid=1">แอป AI Automation (OpenClaw)</a></li>
                <li><a href="/billing/gpu_cloud.php">GPU AI Marketplace (Vast.ai)</a></li>
                <li><a href="/billing/gpu_cloud.php#models">RTX 4090 / 5090 Compute</a></li>
                <li><a href="/billing/cart.php?a=add&domain=register">จดทะเบียนโดเมนใหม่</a></li>
                <li><a href="/billing/cart.php?a=add&domain=transfer">ย้ายโดเมนเนม</a></li>
                <li><a href="/documentation/">คู่มือการใช้งาน (Docs)</a></li>
            </ul>
        </div>

        <div class="cai-footer-col">
            <h4>ระบบสมาชิก & ช่วยเหลือ</h4>
            <ul class="cai-footer-links">
                <li><a href="/billing/clientarea.php">เข้าสู่ระบบสมาชิก WHMCS</a></li>
                <li><a href="/billing/cart.php?a=view">ตะกร้าสินค้า</a></li>
                <li><a href="/billing/submitticket.php">เปิดคำร้องแจ้งปัญหา (Ticket)</a></li>
                <li><a href="/billing/index.php?rp=/announcements">ข่าวสารและประกาศ</a></li>
                <li><a href="/billing/index.php?rp=/knowledgebase">ฐานความรู้ (Knowledgebase)</a></li>
                <li><a href="/billing/contact.php">ติดต่อทีมงาน 24/7</a></li>
            </ul>
        </div>
    </div>

    <div class="cai-footer-bottom">
        <div>
            © 2026 CreateAI (createai.site). สงวนลิขสิทธิ์ทุกประการ ขับเคลื่อนด้วย Antler & WHMCS
        </div>
        <div class="cai-payments-list">
            <span style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-right: 6px;">รองรับการชำระเงิน:</span>
            <i class="fab fa-cc-visa" title="Visa"></i>
            <i class="fab fa-cc-mastercard" title="Mastercard"></i>
            <i class="fab fa-cc-paypal" title="PayPal"></i>
            <i class="fas fa-qrcode" title="PromptPay QR"></i>
        </div>
    </div>
</footer>

<!-- Interactive Scripts -->
<script src="/antler/assets/js/jquery.min.js"></script>
<script src="/antler/assets/js/bootstrap.min.js"></script>
<script>
// VPS Interactive Slider Data (Updated with real PIDs and Hostinger-grade prices)
const vpsPlans = [
    { cpu: '1 Core', ram: '4 GB', disk: '50 GB', bw: '4 TB', price: '฿199', pid: 5 },
    { cpu: '2 Cores', ram: '8 GB', disk: '100 GB', bw: '8 TB', price: '฿299', pid: 6 },
    { cpu: '4 Cores', ram: '16 GB', disk: '200 GB', bw: '16 TB', price: '฿549', pid: 7 },
    { cpu: '8 Cores', ram: '32 GB', disk: '400 GB', bw: '32 TB', price: '฿1,099', pid: 7 }
];

function setVpsStep(index) {
    document.getElementById('vpsRangeSlider').value = index;
    updateVpsFromSlider(index);
}

function updateVpsFromSlider(val) {
    const idx = parseInt(val, 10);
    const plan = vpsPlans[idx];
    
    document.getElementById('specCpu').textContent = plan.cpu;
    document.getElementById('specRam').textContent = plan.ram;
    document.getElementById('specDisk').textContent = plan.disk;
    document.getElementById('specBw').textContent = plan.bw;
    document.getElementById('specPrice').innerHTML = plan.price + ' <span style="font-size: 14px; color: #cbd5e1; font-weight: 400;">/ด.</span>';
    document.getElementById('specBtn').href = '/billing/cart.php?a=add&pid=' + plan.pid;

    // Update marker active styles
    const markers = document.querySelectorAll('.cai-step-marker');
    markers.forEach((m, i) => {
        if (i === idx) {
            m.classList.add('active');
        } else {
            m.classList.remove('active');
        }
    });
}

// 4 Pillars Workflow Tab Switcher
function switchTab(index) {
    const tabs = document.querySelectorAll('.cai-workflow-tab-btn');
    tabs.forEach((tab, i) => {
        if (i === index) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });

    for (let i = 0; i < 4; i++) {
        const panel = document.getElementById('tabPanel' + i);
        if (panel) {
            if (i === index) {
                panel.classList.add('active');
            } else {
                panel.classList.remove('active');
            }
        }
    }
}

// FAQ Accordion Toggle
function toggleFaq(el) {
    const item = el.parentElement;
    item.classList.toggle('active');
}
</script>

</body>
</html>
