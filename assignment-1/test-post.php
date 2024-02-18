<?php

// // Database connection parameters
// $host = 'localhost';
// $username = 'root';
// $password = '';
// $database = 'social_sms';

// // Sample titles and descriptions
// $titles = [
//     "Introduction to PHP",
//     "Building a Blog with MySQL",
//     "Web Development Best Practices",
//     "Creating Responsive Websites",
//     "Understanding Object-Oriented Programming",
//     "Data Visualization Techniques",
//     "Exploring Machine Learning",
//     "Cybersecurity Fundamentals",
//     "Cloud Computing Solutions",
//     "E-commerce Trends and Strategies"
// ];

// $descriptions = [
//     "This course provides an introduction to PHP programming language.",
//     "Learn how to build a blog application using MySQL database.",
//     "Best practices for web development including HTML, CSS, and JavaScript.",
//     "Techniques for creating responsive websites that work well on all devices.",
//     "Understand the principles of object-oriented programming and how to apply them.",
//     "Explore various techniques for visualizing data effectively.",
//     "Introduction to machine learning algorithms and applications.",
//     "Fundamentals of cybersecurity including threat detection and prevention.",
//     "An overview of cloud computing solutions and their benefits.",
//     "Trends and strategies in e-commerce industry."
// ];

// try {
//     // Connect to the database
//     $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     // Prepare the INSERT statement
//     $stmt = $pdo->prepare("INSERT INTO post (title, cat_id, description, user_id, created_at) VALUES (?, ?, ?, ?, NOW())");

//     // Insert data into the post table
//     for ($i = 0; $i < 20; $i++) {
//         $title = $titles[array_rand($titles)];
//         $description = $descriptions[array_rand($descriptions)];
//         $cat_id = mt_rand(5, 6); // Random category ID between 5 and 6
//         $user_id = mt_rand(46, 64); // Random user ID between 46 and 64
        
//         // Bind parameters and execute the statement
//         $stmt->execute([$title, $cat_id, $description, $user_id]);
//     }

//     echo "Posts inserted successfully!";
// } catch (PDOException $e) {
//     die("Error: " . $e->getMessage());
// }
// Include the DatabaseConnection class

require_once './database/database_connection.php'; // Include your DatabaseConnection class file here


// Create a new instance of DatabaseConnection
$db = new DatabaseConnection('localhost', 'root', '', 'social_sms');

// Test: Retrieve posts with text 'technology' in fname, lname, title, or description
$text = '%Jah%';
$searchConditions = [
    ['fname' => $text],
    ['lname' => $text],
    ['title' => $text],
    ['description' => $text],
    ['cat_id'=>5],
    'text'=>'g'
];
$textPosts = $db->selectWithConditions('post','', $searchConditions);
echo "Posts with 'technology' in fname, lname, title, or description:\n";
echo"<pre>";
print_r($textPosts);
echo"</pre>";

echo "</br>";

