<?php
require_once 'db_connect.php';

// disable foreign key checks to avoid issues with missing users
$conn->query("SET FOREIGN_KEY_CHECKS=0");

echo "Seeding database with initial content...\n";

// --- Seed Events ---
$count = $conn->query("SELECT COUNT(*) as c FROM events")->fetch_assoc()['c'];
if ($count == 0) {
    echo "Seeding Events...\n";
    $events = [
        [
            'Sunday Divine Service',
            'Join us for a powerful time of worship and the word.',
            '2025-01-26 07:00:00',
            null,
            'Main Auditorium'
        ],
        [
            'Mid-Week Bible Studies',
            'Deep dive into the scriptures.',
            '2025-01-28 18:30:00',
            null,
            'L-Block'
        ],
        [
            'Mega All-Night Service',
            'A night of prayer and warfare.',
            '2025-02-02 21:00:00',
            null,
            'Main Auditorium'
        ]
    ];

    $stmt = $conn->prepare("INSERT INTO events (title, description, start_date, end_date, location, created_by, status) VALUES (?, ?, ?, ?, ?, 1, 'published')");
    foreach ($events as $evt) {
        $stmt->bind_param("sssss", $evt[0], $evt[1], $evt[2], $evt[3], $evt[4]);
        $stmt->execute();
    }
    echo "Inserted " . count($events) . " events.\n";
} else {
    echo "Events table already has data.\n";
}

// --- Seed Resources ---
$count = $conn->query("SELECT COUNT(*) as c FROM resources")->fetch_assoc()['c'];
if ($count == 0) {
    echo "Seeding Resources...\n";
    $resources = [
        ['PENSA UMaT Constitution', 'The official guiding document.', '#', 'pdf', 'constitution'],
        ['Sunday Service Sermon: Covenant Keeping God', 'Notes from Sunday message.', '#', 'doc', 'sermon'],
        ['Worship Medley - 31st Night', 'High quality worship session.', '#', 'mp3', 'audio'],
        ['Department Transfer Form', 'Form for members wishing to switch.', '#', 'pdf', 'form'],
        ['Bible Study Manual - Semester 2', 'Complete guide and study topics.', '#', 'doc', 'sermon']
    ];

    $stmt = $conn->prepare("INSERT INTO resources (title, description, file_url, file_type, category, uploaded_by, status) VALUES (?, ?, ?, ?, ?, 1, 'published')");
    foreach ($resources as $res) {
        $stmt->bind_param("sssss", $res[0], $res[1], $res[2], $res[3], $res[4]);
        $stmt->execute();
    }
    echo "Inserted " . count($resources) . " resources.\n";
} else {
    echo "Resources table already has data.\n";
}

// --- Seed Gallery Albums ---
$count = $conn->query("SELECT COUNT(*) as c FROM gallery_albums")->fetch_assoc()['c'];
if ($count == 0) {
    echo "Seeding Gallery Albums...\n";
    $albums = [
        ['Sunday Service - Covenant Day', '2025-01-26', 'Main Auditorium'],
        ['31st Watch Night Service', '2024-12-31', 'UMaT Great Hall'],
        ['Alumni Homecoming', '2024-11-15', 'Campus'],
        ['Mega Evangelism Outreach', '2024-10-10', 'Tarkwa Township']
    ];

    $stmt = $conn->prepare("INSERT INTO gallery_albums (title, event_date, location, created_by) VALUES (?, ?, ?, 1)");
    // Prepared statement for photos
    $stmt_photo = $conn->prepare("INSERT INTO gallery (album_id, image_url, uploaded_by) VALUES (?, ?, 1)");

    // Sample images mapped to albums roughly
    $album_images = [
        0 => ['images/banners/home-slider-3.jpg', 'images/banners/home-slider-2.jpg', 'images/banners/home-slider-1.jpg'],
        1 => ['images/banners/slide6.jpg', 'images/banners/slide10.jpg', 'images/banners/music and drama.jpg'],
        2 => ['images/banners/alumni-2.jpg', 'images/banners/alumni homecoming.jpg', 'images/banners/about us.jpg'],
        3 => ['images/banners/Evangelism Department.jpg', 'images/banners/prayer department.jpg']
    ];

    $i = 0;
    foreach ($albums as $alb) {
        $stmt->bind_param("sss", $alb[0], $alb[1], $alb[2]);
        if ($stmt->execute()) {
            $album_id = $stmt->insert_id;
            if (isset($album_images[$i])) {
                foreach ($album_images[$i] as $img) {
                    $stmt_photo->bind_param("is", $album_id, $img);
                    $stmt_photo->execute();
                }
            }
        }
        $i++;
    }
    echo "Inserted " . count($albums) . " albums and their photos.\n";
} else {
    echo "Gallery Albums table already has data.\n";
}

$conn->close();
?>
