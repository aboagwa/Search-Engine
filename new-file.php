<?php
include("db-connection.php");
include("header.php");

$rss_urls = [
    "https://apod.nasa.gov/apod.rss",
    "https://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml",
    "https://techcrunch.com/feed/",
    "http://rss.cnn.com/rss/cnn_topstories.rss"
];

foreach($rss_urls as $rss_url){

    $ch= curl_init();
    curl_setopt($ch,CURLOPT_URL,$rss_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36");

    $response_url = curl_exec($ch);


    curl_close($ch);

    $rss = simplexml_load_string($response_url);

    if ($rss === false){

        echo "faild";
    }
        else{
            foreach ($rss->channel->item as $item) {
                $title = mysqli_real_escape_string($conn, $item->title);
                $link = mysqli_real_escape_string($conn, $item->link);
                $description = mysqli_real_escape_string($conn, $item->description);

                $sql = "INSERT INTO rss (title,link,description) VALUES ('$title','$link','$description')";
                mysqli_query($conn, $sql);

            }
    }
}


if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']);

    $sql = "SELECT * FROM rss 
            WHERE title LIKE '%$search%' 
            OR description LIKE '%$search%' 
            ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
            echo "<p><a href='" . htmlspecialchars($row['link']) . "'></a></p>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<hr>";
        }
    } else {
        echo "No results found.";
    }
}

?>