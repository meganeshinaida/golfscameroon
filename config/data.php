<?php

function get_info_cards() {
    return [
        [
            'title' => 'Who?',
            'description' => 'The Golfs Cameroon is a youth-focused organization committed to raising future leaders, professionals, and entrepreneurs. We align our work with the United Nations Sustainable Development Goals to drive meaningful, lasting change.'
        ],
        [
            'title' => 'What?',
            'description' => 'We empower young people through education support, mentorship, leadership engagement, and community outreach. From school visits to community support initiatives, we inspire responsibility, hard work, and positive impact.'
        ],
        [
            'title' => 'Where?',
            'description' => 'Registered in Cameroon, we operate locally and internationally. Our work extends across Cameroon, Liberia, and Equatorial Guinea as we build a growing network of changemakers.'
        ]
    ];
}

function get_statistics() {
    return [
        [
            'number' => '51%',
            'description' => 'of young people in underserved communities still lack equal access to quality educational and leadership opportunities. When youth are held back, communities fall behind.'
        ],
        [
            'number' => '1 in 7',
            'description' => 'youths lacks the support system needed to develop their talents and skills. Our mission is to bridge that gap and prepare young people to shape the future.'
        ],
        [
            'number' => '50%',
            'description' => 'of young people complete school each year without access to career guidance or practical leadership training. Education alone is not enough without direction and opportunity.'
        ]
    ];
}

function get_service_cards() {
    return [
        [
            'title' => 'Leadership Development',
            'description' => 'Mentorship, coaching, and youth leadership engagement programs designed to raise confident and purpose-driven changemakers.',
            'image' => 'uploads/leadership.jpg'
        ],
        [
            'title' => 'Education & School Engagement',
            'description' => 'School visits, student recognition, and academic empowerment initiatives that promote discipline, excellence, and growth.',
            'image' => 'uploads/school_engagement.jpg'
        ],
        [
            'title' => 'Community Outreach',
            'description' => 'Community support initiatives and social impact activities that strengthen underserved areas and promote collective development.',
            'image' => 'uploads/community_outreach.png'
        ],
        [
            'title' => 'Global Partnerships',
            'description' => 'International collaborations and cross-border youth engagement across Cameroon, Liberia, and Equatorial Guinea.',
            'image' => 'uploads/global_patnership.jpg'
        ]
    ];
}

function get_involvement_options() {
    return [
        [
            'title' => 'Become a Volunteer',
            'description' => 'Mentorship, coaching, and youth leadership engagement programs designed to raise confident and purpose-driven changemakers.',
            'image' => 'uploads/leadership.jpg',
            'link' => 'members',
            'button_text' => 'Join us'
        ],
        [
            'title' => 'Partner With Us',
            'description' => 'Collaborate with us as an organization, institution, or corporate body. Together, we can expand opportunities for youth across borders.',
            'image' => 'uploads/global_patnership.jpg',
            'link' => 'members',
            'button_text' => 'Partner now'
        ],
        [
            'title' => 'Support the Mission',
            'description' => 'Contribute resources that help us run leadership programs and community outreach initiatives. Every contribution helps shape future changemakers.',
            'image' => 'uploads/leadership.jpg',
            'link' => 'donations',
            'button_text' => 'Support Now'
        ],
        [
            'title' => 'Join the Youth Network',
            'description' => 'Are you a young leader or aspiring professional? Connect with a growing network of purpose-driven youth.',
            'image' => 'uploads/leadership.jpg',
            'link' => 'members',
            'button_text' => 'Join the Network'
        ]
    ];
}

/**
 * FAQ Data
 */
function get_faqs() {
    return [
        [
            'question' => 'What about boys?',
            'answer' => 'Whether you choose to sponsor a girl or a boy, you\'ll help projects focused on equal opportunities for all children. We know girls are the most vulnerable and we ensure that boys play an important role in building secure communities that value girls.'
        ],
        [
            'question' => 'Is sponsorship like adoption?',
            'answer' => 'No, it\'s not. The girl you sponsor will have a family of her own, but your words of encouragement play an important role for her and her community. Letter writing also helps girls learn about other cultures and improve their literacy skills.'
        ],
        [
            'question' => 'How can I get more information?',
            'answer' => 'Our Supporter Engagement team are happy to answer any questions about sponsorship and our work. You can reach them on 0300 777 9779 or supporterquestions@plan-uk.org.'
        ]
    ];
}

/**
 * Focus Areas/Services Data for Services Page
 */
function get_focus_areas() {
    return [
        [
            'id' => 'leadership_development',
            'title' => 'Leadership Development',
            'subtitle' => 'Building confident, purpose-driven changemakers',
            'content'=>'Our Leadership Development programs are designed to equip young people with the mindset, skills, and guidance they need to become responsible leaders in their communities and beyond.',
            'image' => 'uploads/leadership.jpg',
            'what_we_do' => [
                'Provide mentorship and coaching led by experienced role models',
                'Engage youths in leadership discussions, workshops, and activities',
                'Encourage self-confidence, critical thinking, and positive values'
            ],
            'why_it_matters' => 'By nurturing leadership at an early stage, we help youths discover their potential, define their purpose, and take active roles in driving positive social change.'
        ],
        [
            'id' => 'education_engagement',
            'title' => 'Education & School Engagement',
            'subtitle' => 'Empowering students through discipline, excellence, and opportunity',
            'content'=>'Education remains a cornerstone of our work. Through direct engagement with schools, we motivate students to value hard work, discipline, and lifelong learning.',
            'image' => 'uploads/school_engagement.jpg',
            'what_we_do' => [
                'Conduct school visits to inspire and educate students',
                'Recognize and reward hardworking and high-performing students',
                'Promote academic excellence and personal growth through motivation sessions'
            ],
            'why_it_matters' => 'By encouraging students and celebrating achievement, we help create an environment where young people believe in themselves and strive for excellence.'
        ],
        [
            'id' => 'community_outreach',
            'title' => 'Community Outreach',
            'subtitle' => 'Strengthening communities through collective action',
            'content'=>'Our Community Outreach initiatives focus on supporting underserved communities and addressing social needs through practical action and compassion.',
            'image' => 'uploads/community_outreach.png',
            'what_we_do' => [
                'Organize community visits and outreach programs',
                'Distribute essential items and support local initiatives',
                'Collaborate with community leaders to identify pressing needs'
            ],
            'why_it_matters' => 'By strengthening local communities, we help create resilient support systems where young people can thrive and contribute meaningfully.'
        ],[
            'id' => 'global_partnerships',
            'title' => 'Global Partnerships',
            'subtitle' => 'Creating impact through cross-border collaboration',
            'content'=>'Our partnerships span Cameroon, Liberia, and Equatorial Guinea, aligning with global development goals and regional cooperation.',
            'image' => 'uploads/global_patnership.jpg',
            'what_we_do' => [
                'Build international collaborations and youth exchange initiatives',
                'Support cross-border engagement between young leaders',
                'Promote shared learning and cultural understanding'
            ],
            'why_it_matters' => 'By connecting youths globally, we broaden perspectives, inspire innovation, and strengthen networks that drive sustainable change.'
        ]
    ];
}

/**
 * Services/Features Cards for Services Page Bottom Section
 */
function get_service_features() {
    return [
        [
            'title' => 'Community Assistance',
            'description' => 'Direct support and resources for communities in need.'
        ],
        [
            'title' => 'Youth Mentorship',
            'description' => 'Mentorship programs connecting youth with role models.'
        ],
        [
            'title' => 'Educational Outreach',
            'description' => 'Workshops and resources to improve learning outcomes.'
        ],
        [
            'title' => 'Webinars & Workshops',
            'description' => 'Online and offline events for skills and capacity building.'
        ],
        [
            'title' => 'Scholarships',
            'description' => 'Financial support to promising students.'
        ]
    ];
}
?>
