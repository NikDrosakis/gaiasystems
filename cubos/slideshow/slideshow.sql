CREATE TABLE IF NOT EXISTS wid_slideshow (
                                             id INT AUTO_INCREMENT PRIMARY KEY,
                                             filename VARCHAR(255) NOT NULL,
    sort INT NOT NULL DEFAULT 0,
    caption TEXT
    );
