<?php
add_shortcode("BusinessExcellenceAwardsPage", "BusinessExcellenceAwardsPage");
function BusinessExcellenceAwardsPage() {
    ob_start();
    ?>
    <style>
    .bea-bg {
        background: #14233a;
        color: #fff;
        min-height: 100vh;
        padding: 0;
        font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    }
    .bea-gold-border {
        border: 4px solid #c9a13b;
        border-radius: 18px;
        padding: 32px 18px 18px 18px;
        margin: 32px auto 0 auto;
        background: #14233a;
        max-width: 900px;
        box-shadow: 0 0 24px 0 rgba(201,161,59,0.15);
    }
    .bea-header-logo {
        width: 120px;
        margin: 0 auto 16px auto;
        display: block;
    }
    .bea-trophy {
        width: 220px;
        margin: 0 auto 16px auto;
        display: block;
    }
    .bea-title {
        color: #c9a13b;
        font-size: 2.2rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 0.5rem;
        letter-spacing: 1px;
    }
    .bea-subtitle {
        color: #fff;
        font-size: 1.2rem;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .bea-section-title {
        color: #c9a13b;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 2rem 0 1rem 0;
        text-align: center;
    }
    .bea-gold-line {
        border-top: 2px solid #c9a13b;
        margin: 1.5rem 0 1.5rem 0;
    }
    .bea-content {
        color: #fff;
        font-size: 1.08rem;
        margin-bottom: 1.5rem;
        text-align: justify;
    }
    .bea-award-list {
        background: #14233a;
        border: 2px solid #c9a13b;
        border-radius: 12px;
        padding: 1.5rem 1rem;
        margin: 2rem 0;
        color: #fff;
    }
    .bea-award-list li {
        border-bottom: 1px solid #c9a13b;
        padding: 0.5rem 0;
        margin: 0 0 0.2rem 0;
        font-size: 1.08rem;
        list-style: decimal inside;
    }
    .bea-award-list li:last-child {
        border-bottom: none;
    }
    .bea-sponsors {
        background: #14233a;
        border: 2px solid #c9a13b;
        border-radius: 12px;
        padding: 1.5rem 1rem;
        margin: 2rem 0;
        text-align: center;
    }
    .bea-sponsor-logos {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
        padding: 1rem;
    }
    .bea-sponsor-logo {
        width: 100%;
        height: 80px;
        object-fit: contain;
        background: #fff;
        border-radius: 8px;
        padding: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .bea-footer {
        text-align: center;
        color: #fff;
        font-size: 1rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .bea-footer-logo {
        width: 90px;
        margin: 0 auto 0.5rem auto;
        display: block;
    }
    .bea-gold-text {
        color: #c9a13b;
        font-weight: bold;
    }
    .bea-nomination-link {
        color: #fff;
        font-size: 1.2rem;
        font-weight: bold;
        text-align: center;
        margin: 2rem 0 1rem 0;
        display: block;
        text-decoration: underline;
    }
    .bea-event-details {
        background: #c9a13b;
        color: #14233a;
        border-radius: 20px;
        padding: 1.5rem;
        margin: 2rem 0;
        text-align: center;
        font-weight: bold;
    }
    .bea-guest-section {
        margin: 2rem 0;
        text-align: center;
    }
    .bea-guest-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 1rem auto;
        display: block;
        border: 4px solid #c9a13b;
    }
    .bea-guest-name {
        color: #fff;
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .bea-guest-title {
        color: #fff;
        font-size: 1rem;
    }
    .bea-guests-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin: 1rem 0;
    }
    .bea-guest-item {
        color: #fff;
        text-align: left;
        padding: 0.5rem;
    }
    .bea-guest-item strong {
        color: #c9a13b;
    }
    .bea-jury-section {
        margin: 2rem 0;
    }
    .bea-jury-member {
        margin: 1rem 0;
        color: #fff;
    }
    .bea-jury-name {
        color: #c9a13b;
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    .bea-jury-details {
        font-size: 0.95rem;
        line-height: 1.4;
    }
    .bea-header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .bea-header-left, .bea-header-right {
        text-align: center;
        flex: 0 0 150px;
    }
    .bea-header-center {
        flex: 1;
        text-align: center;
    }
    .bea-header-logo-small {
        width: 60px;
        height: 60px;
        margin-bottom: 0.5rem;
    }
    .bea-header-text {
        color: #fff;
        font-size: 0.9rem;
        margin-bottom: 0.2rem;
    }
    .bea-header-text-small {
        color: #c9a13b;
        font-size: 0.8rem;
    }
    @media (max-width: 600px) {
        .bea-gold-border {
            padding: 12px 2px 2px 2px;
        }
        .bea-award-list {
            padding: 1rem 0.2rem;
        }
        .bea-sponsor-logos {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        .bea-sponsor-logo {
            height: 60px;
        }
        .bea-guests-grid {
            grid-template-columns: 1fr;
        }
        .bea-header-section {
            flex-direction: column;
            gap: 1rem;
        }
        .bea-header-left, .bea-header-right {
            flex: none;
        }
    }
    </style>
    <div class="bea-bg">
        <div class="bea-gold-border">
            <!-- Header Section -->
            <div class="bea-header-section">
                <div class="bea-header-left">
                    <img src="https://via.placeholder.com/80x80?text=Trophy+Logo" alt="Trophy Logo" class="bea-header-logo-small" />
                    <div class="bea-header-text">AP CHAMBERS BUSINESS EXCELLENCE AWARDS 2025</div>
                    <div class="bea-header-text-small">AP CHAMBERS</div>
                </div>
                <div class="bea-header-center">
                    <div class="bea-title" style="font-size: 2rem; margin: 0;">Business Excellence Award Categories for 2025</div>
                </div>
                <div class="bea-header-right">
                    <img src="https://via.placeholder.com/80x80?text=AP+Logo" alt="AP Chambers Logo" class="bea-header-logo-small" />
                    <div class="bea-header-text-small">AP CHAMBERS</div>
                </div>
            </div>

            <!-- Award Categories List -->
            <div class="bea-award-list">
                <ol>
                    <li>Best MSME Company of the Year (Micro & Small)</li>
                    <li>Best MSME Company of the Year (Medium)</li>
                    <li>Best Company of the Year in Large Category</li>
                    <li>Best Start-up of the Year</li>
                    <li>Best Company of the Year in Exports</li>
                    <li>Best Company of the Year in Food Processing (Including Aqua)</li>
                    <li>Best Company of the Year in Tourism & Hospitality</li>
                    <li>Best Company of the Year in Textiles</li>
                    <li>Best Company of the Year in Logistics</li>
                    <li>Best Company of the Year in Infrastructure & Real Estate</li>
                    <li>Best Company of the Year in Circular Economy (Waste Management & Recycling)</li>
                    <li>Best Company of the Year in Automobiles</li>
                    <li>Best CSR Initiative of the Year</li>
                    <li>Best Women Entrepreneur of the Year</li>
                    <li>Life-time Achievement Award</li>
                </ol>
            </div>

            <!-- Event Details -->
            <div class="bea-event-details">
                <div style="font-size: 1.2rem; margin-bottom: 0.5rem;">Date & Time: 11 September 2025 at 6 p.m.</div>
                <div style="font-size: 1.2rem;">Venue: Murali Resorts, Poranki, Vijayawada</div>
            </div>

            <!-- Chief Guest Section -->
            <div class="bea-guest-section">
                <div class="bea-section-title">Chief Guest</div>
                <img src="https://via.placeholder.com/150x150?text=Chief+Guest" alt="Chief Guest" class="bea-guest-photo" />
                <div class="bea-guest-name">Sri Nara Lokesh garu</div>
                <div class="bea-guest-title">Hon'ble Minister for HRD, ITE&C, and RTG</div>
            </div>

            <!-- Special Guests Section -->
            <div class="bea-guest-section">
                <div class="bea-section-title">Special Guests</div>
                <div class="bea-guests-grid">
                    <div class="bea-guest-item">
                        <strong>Sri K. Ram Mohan Naidu garu:</strong><br>
                        Hon'ble Union Minister of Civil Aviation
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri Bhupathi Raju Srinivasa Varma garu:</strong><br>
                        Hon'ble Union Minister of State for Heavy Industries
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri Kollu Ravindra garu:</strong><br>
                        Hon'ble Minister for Mines, Geology, and Excise
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri Kondapalli Srinivas garu:</strong><br>
                        Hon'ble Minister for MSME, SERP, and NRI Empowerment & Relations
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri Satya Kumar Yadav garu:</strong><br>
                        Hon'ble Minister for Health, Family Welfare, and Medical Education
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri Nadendla Manohar garu:</strong><br>
                        Hon'ble Minister for Food and Civil Supplies, Consumer Affairs
                    </div>
                    <div class="bea-guest-item">
                        <strong>Dr. Pemmasani Chandra Sekhar garu:</strong><br>
                        Hon'ble Union Minister of State for Rural Development & Communication
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri Kolusu Parthasarathy garu:</strong><br>
                        Hon'ble Minister for Housing and I&PR
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri T.G. Bharath garu:</strong><br>
                        Hon'ble Minister for Industries & Commerce and Food Processing
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri Kandula Durgesh garu:</strong><br>
                        Hon'ble Minister for Tourism, Culture, and Cinematography
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri Kesineni Sivanath garu:</strong><br>
                        Hon'ble Member of Parliament (Vijayawada)
                    </div>
                    <div class="bea-guest-item">
                        <strong>Sri Lavu Sri Krishna Devarayalu garu:</strong><br>
                        Hon'ble Member of Parliament (Narasaraopet)
                    </div>
                </div>
            </div>

            <!-- Jury Section -->
            <div class="bea-jury-section">
                <div class="bea-section-title">Jury</div>
                <div class="bea-jury-member">
                    <div class="bea-jury-name">Sri J. Satyanarayana, IAS (Retd.)</div>
                    <div class="bea-jury-details">
                        Chief Advisor, Centre for the Fourth Industrial Revolution (C4IR) India, World Economic Forum<br>
                        Former Chairman, Unique Identification Authority of India (UIDAI)<br>
                        Former Secretary, Department of Electronics & Information Technology, Government of India
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <div class="bea-jury-member">
                        <div class="bea-jury-name">Sri Nimmagadda Ramesh Kumar, IAS (Retd.)</div>
                        <div class="bea-jury-details">
                            Director-General, Administrative Staff College of India (ASCI), Hyderabad<br>
                            Former AP State Election Commissioner
                        </div>
                    </div>
                    <div class="bea-jury-member">
                        <div class="bea-jury-name">Sri Ajit Rangnekar</div>
                        <div class="bea-jury-details">
                            Director General, Research & Innovation Circle of Hyderabad (RICH)<br>
                            Former Dean, Indian School of Business (ISB), Hyderabad
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div class="bea-content">
                Andhra Pradesh Chambers of Commerce and Industry Federation (AP Chambers) is a proactive, industry-led, non-governmental, and not-for-profit organisation committed to advancing the interests of trade, commerce, and industry in Andhra Pradesh. Through continuous policy advocacy and constructive engagement with both State and Central governments, AP Chambers plays a vital role in shaping economic reforms, promoting entrepreneurship, and supporting sustainable growth across sectors. It is the largest industry federation in the State with a diverse membership of approximately 1,400 corporate members, 78 affiliated state and district-level associations, and overall reach of around 40,000 members.
            </div>

            <div class="bea-section-title">AP Chambers Business Excellence Awards</div>
            <div class="bea-content">
                AP Chambers has now instituted the prestigious <span class="bea-gold-text">'AP Chambers Business Excellence Awards'</span> to honour outstanding enterprises and entrepreneurs for their significant contribution to the economic and social growth of Andhra Pradesh. These awards celebrate innovation, resilience, and excellence across key sectors that drive our State's economic growth.<br><br>
                The annual awards will honour outstanding contributions in diverse sectors such as MSME, Start-ups, Food Processing, Textiles, Automobiles, Tourism & Hospitality, Women Entrepreneurship, Exports, etc. The awards aim to celebrate success stories while motivating others to pursue excellence. They will be exclusively conferred upon companies located or registered in Andhra Pradesh.<br><br>
                By recognising exemplary performance, the award aims to create role models, promote a culture of competitiveness, and inspire the next generation of businesses and leaders. This initiative also aligns with State Government's and AP Chambers' vision of strengthening industry-government partnership for inclusive and sustainable growth.
            </div>

            <!-- Sponsors Section -->
            <div class="bea-sponsors">
                <div class="bea-section-title" style="color: #c9a13b; margin-bottom: 1rem;">Awards Sponsors</div>
                <div class="bea-sponsor-logos">
                    <img src="https://via.placeholder.com/200x80?text=60+Margadarshak" class="bea-sponsor-logo" alt="60 Margadarshak" />
                    <img src="https://via.placeholder.com/200x80?text=nexgen+QUALITY+MATTERS" class="bea-sponsor-logo" alt="nexgen QUALITY MATTERS" />
                    <img src="https://via.placeholder.com/200x80?text=MURALI+RESORT" class="bea-sponsor-logo" alt="MURALI RESORT Vijayawada" />
                    <img src="https://via.placeholder.com/200x80?text=K+MV+Group" class="bea-sponsor-logo" alt="K MV Group" />
                    <img src="https://via.placeholder.com/200x80?text=CEH" class="bea-sponsor-logo" alt="CEH" />
                    <img src="https://via.placeholder.com/200x80?text=Jyothi+Granite" class="bea-sponsor-logo" alt="Jyothi Granite Exports" />
                    <img src="https://via.placeholder.com/200x80?text=NAVAIA" class="bea-sponsor-logo" alt="NAVAIA Navata Road Transport" />
                    <img src="https://via.placeholder.com/200x80?text=S+SRAVAN" class="bea-sponsor-logo" alt="S SRAVAN" />
                    <img src="https://via.placeholder.com/200x80?text=mohan" class="bea-sponsor-logo" alt="mohan" />
                    <img src="https://via.placeholder.com/200x80?text=Kumar+PUMPS" class="bea-sponsor-logo" alt="Kumar PUMPS & MOTORS" />
                    <img src="https://via.placeholder.com/200x80?text=CONTINENTAL+COFFEE" class="bea-sponsor-logo" alt="CONTINENTAL COFFEE" />
                    <img src="https://via.placeholder.com/200x80?text=Laila+Nutra" class="bea-sponsor-logo" alt="Laila Nutra" />
                    <img src="https://via.placeholder.com/200x80?text=YERRAMSETTY+RAMBABU" class="bea-sponsor-logo" alt="YERRAMSETTY RAMBABU GROUP" />
                    <img src="https://via.placeholder.com/200x80?text=RAMCOR" class="bea-sponsor-logo" alt="RAMCOR" />
                </div>
            </div>

            <!-- Footer -->
            <div class="bea-footer">
                <img src="https://via.placeholder.com/90x60?text=AP+Chambers+Logo" alt="AP Chambers Logo" class="bea-footer-logo" />
                Andhra Pradesh Chambers of Commerce and Industry Federation<br>
                #40-1-144, 3rd Floor, Corporate Centre, Beside Chandana Grand, Vijayawada - 520 010, Andhra Pradesh<br>
                Ph: 099120 92222<br>
                federation@apchamber.in<br>
                Please visit: www.apchambers.in
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}