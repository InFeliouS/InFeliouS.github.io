-- Create the `post` table
CREATE TABLE `post` (
    `post_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `post_name` VARCHAR(25) NOT NULL,
    `post_content` VARCHAR(100) NOT NULL,
    `post_datetime` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the `comments` table
CREATE TABLE `comments` (
    `comment_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `post_id` INT UNSIGNED NOT NULL, -- Foreign key referencing the post
    `comment_content` VARCHAR(100) NOT NULL, -- The comment itself
    `comment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of the comment
    FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

