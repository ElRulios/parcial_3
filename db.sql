
-- Tabla countries
CREATE TABLE IF NOT EXISTS countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Tabla interests (áreas de interés)
CREATE TABLE IF NOT EXISTS interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Tabla registrants (datos del inscriptor)
CREATE TABLE IF NOT EXISTS registrants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(150) NOT NULL,
    last_name VARCHAR(150) NOT NULL,
    age INT NOT NULL,
    sexo ENUM('M','F','O') NOT NULL,
    country_id INT,
    nationality VARCHAR(100),
    email VARCHAR(255),
    phone VARCHAR(50),
    observations TEXT,
    form_date DATE,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL
);

-- Tabla intermedia para relaciones many-to-many
CREATE TABLE IF NOT EXISTS registrant_interests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registrant_id INT NOT NULL,
    interest_id INT NOT NULL,
    FOREIGN KEY (registrant_id) REFERENCES registrants(id) ON DELETE CASCADE,
    FOREIGN KEY (interest_id) REFERENCES interests(id) ON DELETE CASCADE
);

-- Datos iniciales para countries (ejemplo)
INSERT INTO countries (name) VALUES
('Panama'),
('Costa Rica'),
('Colombia'),
('Mexico'),
('United States');

-- Datos iniciales para interests (áreas de interés)
INSERT INTO interests (name) VALUES
('Frontend (React/Vue)'),
('Backend (Node.js/PHP)'),
('DevOps / Cloud'),
('Data Science / ML'),
('Mobile (Flutter/React Native)'),
('Seguridad / Ethical Hacking');