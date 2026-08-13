<?php
/**
 * data.php
 * Central data store for the site. In a production build this would come
 * from a database — kept as structured PHP arrays here so the whole site
 * runs on Core PHP with zero external dependencies.
 */

$site = [
    'name'      => 'Strata & Beam Engineering',
    'shortName' => 'Strata & Beam',
    'phone'     => '+977-1-4567890',
    'email'     => 'info@strataandbeam.com',
    'address'   => 'Putalisadak Road, Kathmandu 44600, Nepal',
    'founded'   => 2008,
];

$services = [
    [
        'id'    => 'structural-design',
        'icon'  => 'blueprint',
        'title' => 'Structural Engineering & Design',
        'short' => 'Seismic-resilient structural systems for buildings of all scales.',
        'desc'  => 'Comprehensive structural engineering: modeling, analysis and certified design for residential, commercial and industrial projects. Specialised in RCC, structural steel and composite solutions, with designs compliant to Nepal\'s NBC seismic requirements.',
        'features' => ['Seismic and wind-load analysis', 'RCC and steel detailing', 'Foundation and retaining-system design', 'Independent peer review & code compliance'],
    ],
    [
        'id'    => 'site-supervision',
        'icon'  => 'hardhat',
        'title' => 'Site Supervision',
        'short' => 'Daily on-ground quality control from foundation to finish.',
        'desc'  => 'Our resident engineers track every pour, weld and course of masonry against drawing tolerances, keeping contractors accountable and clients informed with weekly progress reporting.',
        'features' => ['Daily site inspection logs', 'Material testing coordination', 'Contractor accountability audits', 'Weekly photo-based progress reports'],
    ],
    [
        'id'    => 'geotechnical',
        'icon'  => 'strata',
        'title' => 'Geotechnical Investigation',
        'short' => 'Soil testing and bearing-capacity reports before you break ground.',
        'desc'  => 'Sub-surface investigation, bore-log analysis and bearing capacity reporting so foundation design starts from real ground data instead of assumptions.',
        'features' => ['Bore hole & SPT testing', 'Bearing capacity reports', 'Slope stability assessment', 'Ground water analysis'],
    ],
    [
        'id'    => 'road-infrastructure',
        'icon'  => 'road',
        'title' => 'Road & Infrastructure',
        'short' => 'Pavement design and drainage planning for public and private roads.',
        'desc'  => 'From rural access roads to municipal pavement rehabilitation, we handle alignment survey, pavement design and stormwater drainage planning.',
        'features' => ['Alignment & topographic survey', 'Flexible & rigid pavement design', 'Stormwater drainage planning', 'Culvert & retaining structures'],
    ],
    [
        'id'    => 'project-management',
        'icon'  => 'blueprint',
        'title' => 'Construction Project Management',
        'short' => 'One accountable team from permits to final handover.',
        'desc'  => 'We coordinate architects, contractors and vendors under a single schedule and budget, so you get one point of accountability from permitting through handover.',
        'features' => ['Cost estimation & BOQ', 'Vendor & schedule coordination', 'Budget tracking', 'Final handover documentation'],
    ],
    [
        'id'    => 'renovation-retrofit',
        'icon'  => 'hardhat',
        'title' => 'Renovation & Seismic Retrofit',
        'short' => 'Strengthening existing structures to meet current safety codes.',
        'desc'  => 'Structural assessment and retrofit design for older buildings — jacketing, bracing and foundation underpinning to bring them up to current seismic standards.',
        'features' => ['Structural health assessment', 'Column & beam jacketing', 'Seismic bracing design', 'Underpinning & foundation repair'],
    ],
];

// The workflow is a genuine ordered sequence, so numbered steps are earned here.
$workflow = [
    ['step' => '01', 'title' => 'Site Assessment',   'desc' => 'Ground survey, soil testing and existing-condition review to establish real constraints before any design starts.'],
    ['step' => '02', 'title' => 'Design & Analysis',  'desc' => 'Structural modelling, load calculations and drawings, reviewed against NBC seismic code and client brief.'],
    ['step' => '03', 'title' => 'Permitting',         'desc' => 'Drawing package prepared and submitted to the municipal authority; we track approval and handle revisions.'],
    ['step' => '04', 'title' => 'Construction',       'desc' => 'Resident supervision through every pour and structural milestone, with weekly reporting back to you.'],
    ['step' => '05', 'title' => 'Quality Testing',    'desc' => 'Concrete cube tests, rebar checks and material verification logged against design tolerances.'],
    ['step' => '06', 'title' => 'Handover',           'desc' => 'Final walkthrough, as-built drawings and completion documentation delivered on close-out.'],
];

$projects = [
    [
        'id' => 1, 'category' => 'commercial', 'year' => 2024,
        'title' => 'Baneshwor Commercial Tower', 'location' => 'New Baneshwor, Kathmandu',
        'summary' => 'A 9-storey mixed-use commercial tower with a base-isolated structural system.',
        'detail'  => 'Structural design and full-time site supervision for a 9-storey RCC commercial tower. The project required a base-isolation system to meet enhanced seismic performance targets requested by the client, along with a two-level basement parking structure below the water table.',
        'scope'   => ['Structural Design', 'Geotechnical Investigation', 'Site Supervision'],
        'stats'   => ['Floors' => '9 + 2 basement', 'Area' => '42,000 sq.ft', 'Duration' => '22 months'],
        'image'   => 'project-1.jpg',
    ],
    [
        'id' => 2, 'category' => 'residential', 'year' => 2023,
        'title' => 'Budhanilkantha Residence Cluster', 'location' => 'Budhanilkantha, Kathmandu',
        'summary' => 'Eight private residences engineered on a shared sloped plot with retaining terraces.',
        'detail'  => 'Full structural design for eight independent residences on a steeply sloped 2-acre plot. Delivered a terraced retaining-wall system that let each home sit on a level pad while sharing a single stormwater drainage network.',
        'scope'   => ['Structural Design', 'Site Supervision', 'Road & Infrastructure'],
        'stats'   => ['Units' => '8 residences', 'Area' => '2 acres', 'Duration' => '14 months'],
        'image'   => 'project-2.jpg',
    ],
    [
        'id' => 3, 'category' => 'infrastructure', 'year' => 2022,
        'title' => 'Sarlahi Rural Access Road', 'location' => 'Sarlahi District',
        'summary' => '11 km of rural access road with culvert crossings and drainage rehabilitation.',
        'detail'  => 'Alignment survey and pavement design for an 11 km rural access road connecting four village clusters. Scope included six reinforced-concrete culvert crossings and a full stormwater drainage rehabilitation to prevent monsoon washout.',
        'scope'   => ['Road & Infrastructure', 'Geotechnical Investigation', 'Construction Project Management'],
        'stats'   => ['Length' => '11 km', 'Culverts' => '6 crossings', 'Duration' => '9 months'],
        'image'   => 'project-3.jpg',
    ],
    [
        'id' => 4, 'category' => 'retrofit', 'year' => 2023,
        'title' => 'Patan Heritage School Retrofit', 'location' => 'Patan, Lalitpur',
        'summary' => 'Seismic retrofit of a 1960s masonry school building, in active use throughout.',
        'detail'  => 'Structural assessment and seismic retrofit of a 1960s unreinforced masonry school building. Work included column jacketing, steel bracing and a new reinforced roof diaphragm, phased across school holidays to avoid disrupting classes.',
        'scope'   => ['Renovation & Seismic Retrofit', 'Structural Design', 'Site Supervision'],
        'stats'   => ['Built' => '1962', 'Floors' => '3', 'Duration' => '11 months'],
        'image'   => 'project-4.jpg',
    ],
    [
        'id' => 5, 'category' => 'commercial', 'year' => 2021,
        'title' => 'Pokhara Lakeside Hotel', 'location' => 'Lakeside, Pokhara',
        'summary' => 'A 40-room boutique hotel built on soft lakeside soil requiring piled foundations.',
        'detail'  => 'Geotechnical investigation revealed poor bearing capacity near the lakeshore, so we designed a piled raft foundation system and supervised construction of the 40-room boutique hotel above it.',
        'scope'   => ['Geotechnical Investigation', 'Structural Design', 'Construction Project Management'],
        'stats'   => ['Rooms' => '40', 'Floors' => '5', 'Duration' => '18 months'],
        'image'   => 'project-5.jpg',
    ],
    [
        'id' => 6, 'category' => 'residential', 'year' => 2020,
        'title' => 'Dhulikhel Hillside Villa', 'location' => 'Dhulikhel, Kavre',
        'summary' => 'A single private villa cantilevered over a hillside slope.',
        'detail'  => 'Structural design for a private hillside villa, including a cantilevered living deck engineered to clear a 30-degree slope without additional ground support columns.',
        'scope'   => ['Structural Design', 'Geotechnical Investigation'],
        'stats'   => ['Area' => '5,200 sq.ft', 'Slope' => '30°', 'Duration' => '10 months'],
        'image'   => 'project-6.jpg',
    ],
];

$gallery = [
    ['id' => 1, 'category' => 'sites',       'title' => 'Foundation pour, Baneshwor Tower',   'image' => 'gallery-1.jpg'],
    ['id' => 2, 'category' => 'structures',  'title' => 'Column reinforcement detailing',      'image' => 'gallery-2.jpg'],
    ['id' => 3, 'category' => 'team',        'title' => 'Site engineers reviewing drawings',   'image' => 'gallery-3.jpg'],
    ['id' => 4, 'category' => 'completed',   'title' => 'Budhanilkantha Residence Cluster',    'image' => 'gallery-4.jpg'],
    ['id' => 5, 'category' => 'sites',       'title' => 'Formwork, Sarlahi culvert crossing',  'image' => 'gallery-5.jpg'],
    ['id' => 6, 'category' => 'structures',  'title' => 'Steel bracing, school retrofit',      'image' => 'gallery-6.jpg'],
    ['id' => 7, 'category' => 'team',        'title' => 'Geotechnical field survey',           'image' => 'gallery-7.jpg'],
    ['id' => 8, 'category' => 'completed',   'title' => 'Pokhara Lakeside Hotel, exterior',    'image' => 'gallery-8.jpg'],
    ['id' => 9, 'category' => 'sites',       'title' => 'Concrete cube compression testing',   'image' => 'gallery-9.jpg'],
    ['id' => 10,'category' => 'structures',  'title' => 'Slab formwork before pour',           'image' => 'gallery-10.jpg'],
    ['id' => 11,'category' => 'team',        'title' => 'Client walkthrough, Dhulikhel villa', 'image' => 'gallery-11.jpg'],
    ['id' => 12,'category' => 'completed',   'title' => 'Patan Heritage School, post-retrofit','image' => 'gallery-12.jpg'],
];

$team = [
    ['name' => 'Er. Suman Shrestha', 'role' => 'Principal Structural Engineer', 'bio' => '18 years designing seismic-resilient structures across Nepal.', 'image' => 'team-1.jpg'],
    ['name' => 'Er. Anita Gurung',   'role' => 'Geotechnical Lead',            'bio' => 'Specialist in sub-surface investigation and foundation design.', 'image' => 'team-2.jpg'],
    ['name' => 'Er. Bikash Tamang',  'role' => 'Site Operations Manager',      'bio' => 'Runs day-to-day supervision across all active sites.',          'image' => 'team-3.jpg'],
    ['name' => 'Er. Priya Maharjan', 'role' => 'Infrastructure & Roads Lead',  'bio' => 'Leads pavement and drainage design for public works projects.', 'image' => 'team-4.jpg'],
];

$milestones = [
    ['year' => '2008', 'text' => 'Founded in Kathmandu with a two-person structural design office.'],
    ['year' => '2012', 'text' => 'Opened geotechnical division after the Sindhupalchok slope-stability project.'],
    ['year' => '2015', 'text' => 'Post-earthquake retrofit work on 40+ heritage and public buildings.'],
    ['year' => '2019', 'text' => 'Crossed 100 completed structural design projects nationwide.'],
    ['year' => '2023', 'text' => 'Launched dedicated infrastructure & roads division.'],
];

$values = [
    ['title' => 'Code First',    'text' => 'Every design is checked against the National Building Code before it leaves our office.'],
    ['title' => 'On-Site Honesty','text' => 'We report problems the week we find them, not the week before handover.'],
    ['title' => 'Built to Last', 'text' => 'We design for the next earthquake, not just the next inspection.'],
    ['title' => 'Plain Numbers', 'text' => 'Budgets and schedules in writing, updated weekly — no surprise variations.'],
];

$testimonials = [
    ['name' => 'Rajesh Koirala', 'project' => 'Baneshwor Commercial Tower', 'quote' => 'They caught a foundation issue during survey that would have cost us months later. Worth every rupee.'],
    ['name' => 'Sarita Bhattarai', 'project' => 'Budhanilkantha Residence Cluster', 'quote' => 'Eight houses, one retaining wall system, zero drainage disputes between neighbours since handover.'],
    ['name' => 'Principal, Patan Heritage School', 'project' => 'School Seismic Retrofit', 'quote' => 'They worked around our class schedule and the building passed its post-retrofit inspection on the first try.'],
];

$officeHours = [
    'Sunday'    => '10:00 AM – 5:00 PM',
    'Monday'    => '10:00 AM – 5:00 PM',
    'Tuesday'   => '10:00 AM – 5:00 PM',
    'Wednesday' => '10:00 AM – 5:00 PM',
    'Thursday'  => '10:00 AM – 5:00 PM',
    'Friday'    => '10:00 AM – 3:00 PM',
    'Saturday'  => 'Closed',
];

$stats = [
    ['value' => '16+', 'label' => 'Years in Practice'],
    ['value' => '140+', 'label' => 'Projects Delivered'],
    ['value' => '30+', 'label' => 'Engineers on Staff'],
    ['value' => '99%', 'label' => 'Projects Passed First Inspection'],
];
