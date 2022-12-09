<?php
include 'project.php';
$conn = new PDO(Project::DATABASE_HOSTNAME, Project::DATABASE_USERNAME,
    Project::DATABASE_PASSWORD, Project::DATABASE_HOSTNAME);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

