import subprocess
import json

def exec_sql(sql):
    full_sql = "SET sql_mode='';\n" + sql
    cmd = ['docker', 'exec', '-i', 'mysql-server', 'mariadb', '-u', 'root', '-pRootDbPass2026!', 'whmcs_db']
    p = subprocess.run(cmd, input=full_sql, capture_output=True, text=True)
    if p.returncode != 0:
        print(f"SQL Error: {p.stderr}")
    return p.stdout

def seed():
    print("--- 1. Setting up Currencies (USD & THB) ---")
    exec_sql("""
    INSERT INTO tblcurrencies (id, code, prefix, suffix, format, rate) 
    VALUES (1, 'USD', '$', ' USD', 1, 1.00000)
    ON DUPLICATE KEY UPDATE code='USD', prefix='$', suffix=' USD', rate=1.00000;
    
    INSERT INTO tblcurrencies (id, code, prefix, suffix, format, rate) 
    VALUES (2, 'THB', '฿', ' THB', 1, 35.00000)
    ON DUPLICATE KEY UPDATE code='THB', prefix='฿', suffix=' THB', rate=35.00000;
    """)

    print("--- 2. Setting up Product Groups ---")
    exec_sql("""
    INSERT INTO tblproductgroups (id, name, slug, headline, `order`, hidden) VALUES
    (1, 'AI โซลูชัน & ระบบอัตโนมัติ', 'ai-solutions', 'ระบบ Agentic AI อัตโนมัติสำหรับธุรกิจ', 3, 0),
    (2, 'เว็บโฮสติ้งความเร็วสูง (Web Hosting)', 'web-hosting', 'คลาวด์เว็บโฮสติ้ง SSD สปีดสูง มาตรฐานเดียวกับ Hostinger', 1, 0),
    (3, 'คลาวด์ VPS (KVM)', 'vps', 'เซิร์ฟเวอร์เสมือน Dedicated KVM ทรัพยากรแยกเดี่ยว 100%', 2, 0)
    ON DUPLICATE KEY UPDATE name=VALUES(name), slug=VALUES(slug), headline=VALUES(headline), `order`=VALUES(`order`);
    """)

    print("--- 3. Setting up Products ---")
    products = [
        # (id, gid, type, name, slug, description, tagline, short_description, order, is_featured, usd_mo, thb_mo, usd_yr, thb_yr)
        (
            1, 2, 'hostingaccount', 'Single Web Hosting', 'single-web-hosting',
            "✓ 1 เว็บไซต์ (1 Website)\n✓ 50 GB SSD Storage\n✓ 100 GB Bandwidth\n✓ ฟรี SSL Certificate ตลอดชีพ\n✓ 1 บัญชีอีเมลธุรกิจ\n✓ 1-Click ติดตั้ง WordPress\n✓ สำรองข้อมูลรายสัปดาห์",
            "เริ่มต้นทำเว็บไซต์แรกของคุณในราคาประหยัดที่สุด",
            "แพ็กเกจเริ่มต้นราคาประหยัด เหมาะสำหรับบล็อกและเว็บไซต์ส่วนตัว",
            1, 0, 1.49, 29.00, 17.88, 348.00
        ),
        (
            2, 2, 'hostingaccount', 'Premium Web Hosting', 'premium-web-hosting',
            "✓ 100 เว็บไซต์ (100 Websites)\n✓ 100 GB SSD Storage\n✓ Unlimited Bandwidth ไม่จำกัด\n✓ ฟรี โดเมนเนม 1 ปีเต็ม (มูลค่า ฿389)\n✓ ฟรี SSL ไม่จำกัดตลอดชีพ\n✓ 100 บัญชีอีเมลธุรกิจ\n✓ สำรองข้อมูลรายสัปดาห์\n✓ LiteSpeed Web Server แคชเร็ว 5 เท่า",
            "แพ็กเกจยอดนิยมอันดับ 1 สำหรับธุรกิจและเว็บไซต์ทั่วไป",
            "ประสิทธิภาพระดับพรีเมียม จัดการได้ถึง 100 เว็บไซต์ พร้อมฟรีโดเมน",
            2, 1, 1.99, 59.00, 23.88, 708.00
        ),
        (
            3, 2, 'hostingaccount', 'Business Web Hosting', 'business-web-hosting',
            "✓ 100 เว็บไซต์ (100 Websites)\n✓ 200 GB NVMe Storage ความเร็วสูงพิเศษ\n✓ Unlimited Bandwidth ไม่จำกัด\n✓ ฟรี โดเมนเนม 1 ปีเต็ม\n✓ ฟรี SSL Certificate ไม่จำกัด\n✓ สำรองข้อมูลรายวันแบบเรียลไทม์ (Daily Backups)\n✓ ฟรี Cloudflare CDN เพิ่มความเร็วทั่วโลก\n✓ WordPress Staging Tool สำหรับทดสอบระบบ",
            "สำหรับธุรกิจออนไลน์ อีคอมเมิร์ซ และเว็บไซต์ที่ต้องการความเร็วสูงสุด",
            "พลังประมวลผลเพิ่มขึ้น 5 เท่า พื้นที่ NVMe สปีดสูงสุด พร้อมสำรองข้อมูลรายวัน",
            3, 0, 2.99, 89.00, 35.88, 1068.00
        ),
        (
            4, 2, 'hostingaccount', 'Cloud Startup', 'cloud-startup',
            "✓ 300 เว็บไซต์ (300 Websites)\n✓ 200 GB NVMe Storage\n✓ 2 vCPU Cores & 3 GB RAM Dedicated\n✓ 1 Dedicated IPv4 ส่วนตัว\n✓ Unlimited Bandwidth\n✓ ฟรี โดเมนเนม 1 ปีเต็ม\n✓ สำรองข้อมูลรายวันและคืนค่าในคลิกเดียว\n✓ VIP Priority Support ดูแลด่วนพิเศษ 24/7",
            "พลังระดับ Cloud Server พร้อมความง่ายดายของ Web Hosting",
            "ทรัพยากร Dedicated ส่วนตัว ไม่แชร์ใคร เหมาะสำหรับเว็บทราฟฟิกสูง",
            4, 0, 6.99, 209.00, 83.88, 2508.00
        ),
        (
            5, 3, 'server', 'KVM 1 Cloud VPS', 'kvm-1',
            "✓ 1 vCPU Core\n✓ 4 GB RAM\n✓ 50 GB NVMe Disk Space\n✓ 4 TB Bandwidth ความเร็วพอร์ต 300 Mbps\n✓ 1 Dedicated IPv4 + /64 IPv6\n✓ Full Root Access เต็มรูปแบบ\n✓ ติดตั้ง OS อัตโนมัติ (Ubuntu, Debian, CentOS, AlmaLinux, Docker)\n✓ สแนปช็อตสำรองข้อมูลอัตโนมัติ",
            "VPS ประสิทธิภาพสูงสำหรับการเรียนรู้ รันบอท หรือโปรเจกต์ขนาดเล็ก",
            "KVM แท้ 100% สปีดเร็ว ควบคุมผ่านคอนโซลได้อิสระ",
            1, 0, 5.99, 199.00, 59.90, 1990.00
        ),
        (
            6, 3, 'server', 'KVM 2 Cloud VPS', 'kvm-2',
            "✓ 2 vCPU Cores\n✓ 8 GB RAM\n✓ 100 GB NVMe Disk Space\n✓ 8 TB Bandwidth ความเร็วพอร์ต 300 Mbps\n✓ 1 Dedicated IPv4 + /64 IPv6\n✓ Full Root Access เต็มรูปแบบ\n✓ การปกป้อง DDoS Protection ระดับพรีเมียม\n✓ เหมาะสำหรับระบบแอปพลิเคชัน, API Server, และเว็บฐานข้อมูล",
            "แพ็กเกจ VPS ยอดนิยมสำหรับนักพัฒนาและผู้ดูแลระบบ",
            "แรม 8 GB ซีพียู 2 คอร์ รันระบบหนักได้สบายไม่มีสะดุด",
            2, 1, 8.99, 299.00, 89.90, 2990.00
        ),
        (
            7, 3, 'server', 'KVM 4 Cloud VPS', 'kvm-4',
            "✓ 4 vCPU Cores\n✓ 16 GB RAM\n✓ 200 GB NVMe Disk Space\n✓ 16 TB Bandwidth ความเร็วพอร์ต 300 Mbps\n✓ 1 Dedicated IPv4 + /64 IPv6\n✓ Full Root Access เต็มรูปแบบ\n✓ SLA การันตี Uptime 99.9%\n✓ รองรับเวิร์กโหลด AI, Containerization, ไมโครเซอร์วิส",
            "ขุมพลังระดับสูงสำหรับแอปพลิเคชันองค์กรและฐานข้อมูลขนาดใหญ่",
            "แรม 16 GB ซีพียู 4 คอร์ ทรงพลัง รองรับผู้ใช้งานพร้อมกันจำนวนมาก",
            3, 0, 16.99, 549.00, 169.90, 5490.00
        )
    ]

    for p in products:
        pid, gid, ptype, name, slug, desc, tagline, short_desc, order, is_featured, usd_mo, thb_mo, usd_yr, thb_yr = p
        
        # Escape strings for SQL
        esc_name = name.replace("'", "''")
        esc_desc = desc.replace("'", "''")
        esc_tagline = tagline.replace("'", "''")
        esc_short_desc = short_desc.replace("'", "''")

        exec_sql(f"""
        INSERT INTO tblproducts (id, type, gid, name, slug, description, tagline, short_description, `order`, is_featured, tax, showdomainoptions)
        VALUES ({pid}, '{ptype}', {gid}, '{esc_name}', '{slug}', '{esc_desc}', '{esc_tagline}', '{esc_short_desc}', {order}, {is_featured}, 0, 1)
        ON DUPLICATE KEY UPDATE 
            type='{ptype}', gid={gid}, name='{esc_name}', slug='{slug}', description='{esc_desc}', 
            tagline='{esc_tagline}', short_description='{esc_short_desc}', `order`={order}, is_featured={is_featured};
        """)

        # Pricing Currency 1 (USD)
        exec_sql(f"""
        INSERT INTO tblpricing (type, currency, relid, msetupfee, qsetupfee, ssetupfee, asetupfee, bsetupfee, tsetupfee, monthly, quarterly, semiannually, annually, biennially, triennially)
        VALUES ('product', 1, {pid}, 0, 0, 0, 0, 0, 0, {usd_mo}, -1, -1, {usd_yr}, -1, -1)
        ON DUPLICATE KEY UPDATE monthly={usd_mo}, annually={usd_yr};
        """)

        # Pricing Currency 2 (THB)
        exec_sql(f"""
        INSERT INTO tblpricing (type, currency, relid, msetupfee, qsetupfee, ssetupfee, asetupfee, bsetupfee, tsetupfee, monthly, quarterly, semiannually, annually, biennially, triennially)
        VALUES ('product', 2, {pid}, 0, 0, 0, 0, 0, 0, {thb_mo}, -1, -1, {thb_yr}, -1, -1)
        ON DUPLICATE KEY UPDATE monthly={thb_mo}, annually={thb_yr};
        """)
        print(f"Product {pid}: {name} seeded successfully!")

    print("--- 4. Setting up Domain Pricing ---")
    domains = [
        # (extension, order, usd_yr, thb_yr)
        ('.com', 1, 10.99, 389.00),
        ('.net', 2, 13.99, 499.00),
        ('.org', 3, 13.99, 499.00),
        ('.online', 4, 0.99, 35.00),
        ('.site', 5, 0.99, 35.00),
        ('.store', 6, 1.99, 69.00),
        ('.tech', 7, 1.99, 69.00),
        ('.co.th', 8, 23.99, 800.00),
    ]

    for d in domains:
        tld, order, usd_yr, thb_yr = d
        exec_sql(f"""
        INSERT INTO tbldomainpricing (extension, dnsmanagement, emailforwarding, idprotection, eppcode, autoreg, `order`)
        VALUES ('{tld}', 1, 1, 1, 1, '', {order})
        ON DUPLICATE KEY UPDATE `order`={order};
        """)
        
        # Get domain pricing relid
        out = exec_sql(f"SELECT id FROM tbldomainpricing WHERE extension='{tld}';")
        relid = [line.strip() for line in out.splitlines() if line.strip().isdigit()][0]

        # Register price USD
        exec_sql(f"""
        INSERT INTO tblpricing (type, currency, relid, msetupfee, qsetupfee, ssetupfee, asetupfee, bsetupfee, tsetupfee, monthly, quarterly, semiannually, annually, biennially, triennially)
        VALUES ('domainregister', 1, {relid}, 0, 0, 0, 0, 0, 0, -1, -1, -1, {usd_yr}, -1, -1)
        ON DUPLICATE KEY UPDATE annually={usd_yr};
        """)

        # Register price THB
        exec_sql(f"""
        INSERT INTO tblpricing (type, currency, relid, msetupfee, qsetupfee, ssetupfee, asetupfee, bsetupfee, tsetupfee, monthly, quarterly, semiannually, annually, biennially, triennially)
        VALUES ('domainregister', 2, {relid}, 0, 0, 0, 0, 0, 0, -1, -1, -1, {thb_yr}, -1, -1)
        ON DUPLICATE KEY UPDATE annually={thb_yr};
        """)
        print(f"Domain {tld}: ฿{thb_yr}/yr (${usd_yr}/yr) configured!")

    print("--- 5. Configuring WHMCS Defaults in tblconfiguration ---")
    configs = [
        ('CompanyName', 'CreateAI Cloud & Prompt Builder'),
        ('SystemURL', 'http://47.84.242.200/billing/'),
        ('Template', 'antler'),
        ('OrderFormTemplate', 'antler'),
        ('DefaultCountry', 'TH'),
        ('DefaultCurrency', '2'),
        ('Charset', 'utf-8'),
    ]
    for setting, val in configs:
        exec_sql(f"""
        INSERT INTO tblconfiguration (setting, value) VALUES ('{setting}', '{val}')
        ON DUPLICATE KEY UPDATE value='{val}';
        """)

    print("--- 6. Verifying WHMCS Catalog ---")
    verify_sql = """
    SELECT p.id, p.gid, g.name AS group_name, p.name, pr.currency, pr.monthly, pr.annually
    FROM tblproducts p
    JOIN tblproductgroups g ON p.gid = g.id
    LEFT JOIN tblpricing pr ON pr.relid = p.id AND pr.type = 'product'
    ORDER BY p.gid, p.order, p.id, pr.currency;
    """
    print(exec_sql(verify_sql))
    print("WHMCS Hostinger catalog setup complete!")

if __name__ == '__main__':
    seed()
