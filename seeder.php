<?php
// Database Seeder Script for TutorTap
// This script will populate the database with sample data

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "tutortap");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully to database.\n";

// Function to execute a query
function executeQuery($conn, $query) {
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn) . "<br>";
        return false;
    }
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Truncate tables to start fresh (in reverse order of foreign key dependencies)
    $tables = [
        "user_rating", 
        "user_rating_result", 
        "complain", 
        "order", 
        "class_rating", 
        "class_rating_result", 
        "kelas", 
        "chat", 
        "chat_room", 
        "admin", 
        "user"
    ];

    foreach ($tables as $table) {
        executeQuery($conn, "SET FOREIGN_KEY_CHECKS = 0");
        executeQuery($conn, "TRUNCATE TABLE `$table`");
        executeQuery($conn, "SET FOREIGN_KEY_CHECKS = 1");
        echo "Table $table truncated.\n";
    }

    // Seed admin table
    $admins = [
        ["1", "Admin Supervisor"],
        ["2", "Support Admin"],
        ["3", "Technical Admin"]
    ];

    foreach ($admins as $admin) {
        $query = "INSERT INTO admin (adminId, namaAdmin) VALUES ('$admin[0]', '$admin[1]')";
        executeQuery($conn, $query);
    }
    echo "Admin data seeded.\n";

    // Seed user table
    $users = [
        [
            "1001", "Malvin Alexious", "1990-05-15", 
            "Experienced mathematics tutor with 5 years of teaching experience.",
            "javier.png", "Jakarta Selatan", "john@example.com", 
            "password123", "Mathematics Teacher", "1234567890", "500000"
        ],
        [
            "1002", "Lady", "1988-10-20", 
            "Professional English teacher specializing in conversation and grammar.",
            "20221003_133232.jpg", "Bandung", "jane@example.com", 
            "password123", "English Teacher", "2345678901", "750000"
        ],
        [
            "1003", "Jokowi", "1995-03-12", 
            "Computer Science graduate offering programming tutorials in Java and Python.",
            "jokowi.png", "Surabaya", "ali@example.com", 
            "password123", "Software Developer", "3456789012", "250000"
        ],
        [
            "1004", "Stella", "1992-07-25", 
            "Chemistry expert with PhD in Organic Chemistry.",
            "stella.png", "Yogyakarta", "siti@example.com", 
            "password123", "Chemistry Lecturer", "4567890123", "450000"
        ],
        [
            "1005", "David Chen", "1991-12-08", 
            "Physics tutor specializing in mechanics and electromagnetism.",
            "20221003_133232.jpg", "Medan", "david@example.com", 
            "password123", "Physics Teacher", "5678901234", "300000"
        ]
    ];

    foreach ($users as $user) {
        $query = "INSERT INTO user (userId, nama, tanggalLahir, biografi, profilePicture, lokasi, email, password, profesi, noRek, saldo) 
                  VALUES ('$user[0]', '$user[1]', '$user[2]', '$user[3]', '$user[4]', '$user[5]', '$user[6]', '$user[7]', '$user[8]', '$user[9]', '$user[10]')";
        executeQuery($conn, $query);
    }
    echo "User data seeded.\n";    // Seed kelas (class) table    
    $classes = [
        [
            "101", "1001", "Advanced Calculus", "50000", "hour", "1",
            "Comprehensive calculus course covering derivatives, integrals, and applications.",
            "Calc.jpg", "Jakarta Selatan"
        ],
        [
            "102", "1002", "English Grammar Masterclass", "60000", "hour", "1",
            "Complete English grammar course from basic to advanced concepts.",
            "adsiTutoring.jpg", "Bandung"
        ],
        [
            "103", "1003", "Introduction to Python Programming", "60000", "hour", "1",
            "Learn Python programming from scratch with practical examples.",
            "computerSetupGuide.jpg", "Surabaya"
        ],
        [
            "104", "1004", "Organic Chemistry Fundamentals", "50000", "hour", "1",
            "Essential concepts in organic chemistry for high school and university students.",
            "organic_chemistry.webp", "Yogyakarta"
        ],
        [
            "105", "1005", "Physics for Beginners", "70000", "hour", "1",
            "Fundamental physics concepts explained in a simple and engaging way.",
            "physics.jpg", "Medan"
        ],
        [
            "106", "1001", "Linear Algebra Essentials", "80000", "hour", "1",
            "Matrix operations, vector spaces, and applications in computer science.",
            "linear_algebra.png", "Jakarta Selatan"
        ],
        [
            "107", "1005", "Fishing Basics (FOR REAL!)", "50000", "hour", "1",
            "Ready to cast your first line? This \"Fishing Basics\" class is your perfect introduction to the rewarding world of angling. Learn everything you need to get started, from selecting the right rod, reel, and tackle to tying essential knots and baiting your hook. We'll cover fundamental casting techniques, understanding fish habitats, and responsible catch-and-release practices, ensuring a safe and enjoyable experience on the water. Whether you dream of peaceful mornings by the lake or the thrill of a big catch, this class will equip you with the knowledge and confidence to begin your fishing journey.",
            "fishing.jpg", "Sidoarjo"
        ],
        [
            "108", "1005", "Public Speaking", "50000", "hour", "1",
            "Conquer your fear and find your voice in this \"Public Speaking Basics\" class! Designed for anyone looking to communicate with greater confidence and clarity, this course will equip you with the fundamental skills to deliver impactful presentations. You'll learn how to structure your thoughts, craft compelling messages, understand your audience, and master essential delivery techniques including body language, vocal variety, and effective use of visual aids. Through practical exercises and supportive feedback, you'll gain the confidence to speak persuasively and engagingly in any setting, from casual conversations to formal presentations.",
            "public_speaking.jpeg", "Surabaya"
        ],
        [
            "109", "1002", "Painting Basics: Vangogh Style", "65000", "hour", "1",
            "Dive into the vibrant world of post-impressionism with our \"Painting Basics: Vangogh Style\" class! Learn to create stunning artworks inspired by Vincent van Gogh's distinctive technique and emotional intensity. This course covers everything from bold brushstrokes and expressive color palettes to creating movement and emotion in your paintings. Perfect for beginners and intermediate artists alike, you'll explore van Gogh's signature style while developing your own artistic voice. All materials provided in this hands-on class where you'll create your own masterpiece to take home.",
            "vangogh_painting.jpg", "Bandung"
        ],
        [
            "110", "1004", "Knitting Class", "45000", "hour", "1",
            "Unwind and create with our beginner-friendly \"Knitting Class\"! Learn essential knitting techniques from casting on to binding off, and everything in between. This hands-on workshop will teach you different stitches, pattern reading, and how to troubleshoot common knitting mistakes. By the end of the class, you'll have started your first project - a cozy scarf or simple hat. All materials included, and no prior experience necessary. Join us for this relaxing, creative experience where you'll gain a lifelong skill and the satisfaction of creating handmade treasures.",
            "knitting.webp", "Yogyakarta"
        ]
    ];    foreach ($classes as $class) {
        // Escape special characters in the description and other string fields
        $escapedDescription = mysqli_real_escape_string($conn, $class[6]);
        $escapedClassName = mysqli_real_escape_string($conn, $class[2]);
        $escapedLocation = mysqli_real_escape_string($conn, $class[8]);
        
        $query = "INSERT INTO kelas (idKelas, userId, namaKelas, hargaKelas, durasiKelas, statusKelas, deskripsiKelas, fotoKelas, lokasiKelas) 
                  VALUES ('$class[0]', '$class[1]', '$escapedClassName', '$class[3]', '$class[4]', '$class[5]', '$escapedDescription', '$class[7]', '$escapedLocation')";
        executeQuery($conn, $query);
    }
    echo "Class data seeded.\n";

    // Seed class_rating_result table
    $classRatingResults = [
        ["201", "5", "23", "101"],
        ["202", "8", "36", "102"],
        ["203", "4", "19", "103"],
        ["204", "6", "27", "104"],
        ["205", "3", "14", "105"]
    ];

    foreach ($classRatingResults as $result) {
        $query = "INSERT INTO class_rating_result (classRatingResultId, totalRatingCount, totalRatingSum, classId) 
                  VALUES ('$result[0]', '$result[1]', '$result[2]', '$result[3]')";
        executeQuery($conn, $query);
    }
    echo "Class rating results seeded.\n";

    // Seed class_rating table
    $classRatings = [
        ["301", "1002", "201", "5"],
        ["302", "1003", "201", "4"],
        ["303", "1004", "202", "5"],
        ["304", "1005", "202", "4"],
        ["305", "1001", "203", "5"],
        ["306", "1002", "204", "4"],
        ["307", "1003", "205", "5"]
    ];

    foreach ($classRatings as $rating) {
        $query = "INSERT INTO class_rating (ratingId, userId, classRatingResultId, rating) 
                  VALUES ('$rating[0]', '$rating[1]', '$rating[2]', '$rating[3]')";
        executeQuery($conn, $query);
    }
    echo "Class ratings seeded.\n";

    // Seed user_rating_result table
    $userRatingResults = [
        ["401", "18", "4"],
        ["402", "22", "5"],
        ["403", "15", "3"],
        ["404", "20", "4"],
        ["405", "25", "5"]
    ];

    foreach ($userRatingResults as $result) {
        $query = "INSERT INTO user_rating_result (userRatingResultId, totalRatingValue, totalRatingCount) 
                  VALUES ('$result[0]', '$result[1]', '$result[2]')";
        executeQuery($conn, $query);
    }
    echo "User rating results seeded.\n";

    // Seed user_rating table
    $userRatings = [
        ["501", "1002", "401"],
        ["502", "1003", "401"],
        ["503", "1004", "402"],
        ["504", "1005", "402"],
        ["505", "1001", "403"],
        ["506", "1002", "404"],
        ["507", "1003", "405"]
    ];

    foreach ($userRatings as $rating) {
        $query = "INSERT INTO user_rating (ratingId, pemberiId, penerimaId) 
                  VALUES ('$rating[0]', '$rating[1]', '$rating[2]')";
        executeQuery($conn, $query);
    }
    echo "User ratings seeded.\n";

    // Seed chat_room table
    $chatRooms = [
        ["601", "1001"],
        ["602", "1002"],
        ["603", "1003"],
        ["604", "1004"],
        ["605", "1005"]
    ];

    foreach ($chatRooms as $chatRoom) {
        $query = "INSERT INTO chat_room (chatRoomId, userId) 
                  VALUES ('$chatRoom[0]', '$chatRoom[1]')";
        executeQuery($conn, $query);
    }
    echo "Chat rooms seeded.\n";

    // Seed chat table
    $chats = [
        ["701", "1001", "602", "Hello, Im interested in your English class", "2024-05-20 10:15:00"],
        ["702", "1002", "601", "Thank you for your interest. Do you have any questions?", "2024-05-20 10:20:00"],
        ["703", "1003", "604", "When is your next chemistry session?", "2024-05-21 14:30:00"],
        ["704", "1004", "603", "I have slots available this weekend", "2024-05-21 15:45:00"],
        ["705", "1005", "602", "Can you help with advanced English grammar?", "2024-05-22 09:00:00"]
    ];

    foreach ($chats as $chat) {
        $query = "INSERT INTO chat (chatId, senderId, receiverId, pesan, waktu) 
                  VALUES ('$chat[0]', '$chat[1]', '$chat[2]', '$chat[3]', '$chat[4]')";
        executeQuery($conn, $query);
    }
    echo "Chat messages seeded.\n";

    // Seed order table
    $orders = [
        ["801", "1002", "101", "2024-06-01 09:30:00", "2", "Need help with integration techniques", "1", "300000", "12345678", "2024-06-10 14:00:00"],
        ["802", "1003", "102", "2024-06-02 14:15:00", "1", "Focus on business English please", "2", "120000", "23456789", "2024-06-12 16:30:00"],
        ["803", "1004", "103", "2024-06-03 10:00:00", "3", "Beginner with no programming experience", "1", "600000", "34567890", "2024-06-15 09:00:00"],
        ["804", "1005", "104", "2024-06-04 16:45:00", "2", "Preparing for university entrance exam", "2", "360000", "45678901", "2024-06-18 13:45:00"],
        ["805", "1001", "105", "2024-06-05 13:30:00", "1", "Need to understand Newtons laws better", "1", "160000", "56789012", "2024-06-20 10:15:00"]
    ];

    foreach ($orders as $order) {
        $query = "INSERT INTO `order` (idOrder, idUser, idClass, tanggalOrder, jumlahDurasi, catatanOrder, statusOrder, subtotalOrder, vaOrder, jadwalKelas) 
                  VALUES ('$order[0]', '$order[1]', '$order[2]', '$order[3]', '$order[4]', '$order[5]', '$order[6]', '$order[7]', '$order[8]', '$order[9]')";
        executeQuery($conn, $query);
    }
    echo "Orders seeded.\n";

    // Seed complain table
    $complains = [
        ["901", "802", "The tutor was late for our scheduled session", "66697def3ca2f.jpg", "2"],
        ["902", "804", "Quality of teaching did not match the description", "66697e054c309.jpg", "1"]
    ];

    foreach ($complains as $complain) {
        $query = "INSERT INTO complain (complainId, idOrder, complainMessage, complainPicture, adminId) 
                  VALUES ('$complain[0]', '$complain[1]', '$complain[2]', '$complain[3]', '$complain[4]')";
        executeQuery($conn, $query);
    }
    echo "Complaints seeded.\n";

    // Commit changes if everything succeeded
    mysqli_commit($conn);
    echo "All data has been seeded successfully!\n";

} catch (Exception $e) {
    // Roll back transaction on error
    mysqli_rollback($conn);
    echo "Database seeding failed: " . $e->getMessage() . "\n";
}

// Close connection
mysqli_close($conn);
echo "Database connection closed.\n";
?>
