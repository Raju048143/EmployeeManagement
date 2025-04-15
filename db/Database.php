<?php
namespace Employee11\Db;
class Database
{
    private static $instance = null;
    private $conn;
    private $servername = "localhost:3306";
    private $username = "root";
    private $password = "Incapp@12";
    private $dbname = "employee_management";

    private function __construct()
    {
        $this->conn = new \mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance()
     {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    public function emailCheck($email)
    {
        $sql = $this->conn->prepare("SELECT Email FROM Employee WHERE Email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $sql->store_result();
        if ($sql->num_rows > 0) {
            return true;
        }
        return false;
    }

    // Registeration method
    public function register($fullName, $dob, $email, $password, $confirmPassword, $profilePic, $permanentAddress, $currentAddress, $qualifications, $experiences): bool
    {
        try {
            $conn = $this->conn;
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sqlEmployee = "INSERT INTO employee (email, name, dob, password, profileImage) VALUES (?, ?, ?, ?, ?)";
            $stmtEmployee = $conn->prepare($sqlEmployee);
            $stmtEmployee->bind_param('sssss', $email, $fullName, $dob, $hashedPassword, $profilePic);
            $stmtEmployee->execute();
            $stmtEmployee->close();

            $this->insertAddress($conn, 'permanentaddress', $email, $permanentAddress);
            $this->insertAddress($conn, 'currentaddress', $email, $currentAddress);

            if (!empty($qualifications)) {
                $this->insertQualifications($conn, $email, $qualifications);
            }

            if (!empty($experiences)) {
                $this->insertExperiences($conn, $email, $experiences);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    // Iinsert address (current , permanent)
    private function insertAddress($conn, $table, $email, $address)
    {
        $sql = "INSERT INTO $table (employeeEmail, line1, line2, city, state) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $email, ...$address);
        $stmt->execute();
        $stmt->close();
    }

    // Insert qualifications
    private function insertQualifications($conn, $email, $qualifications)
    {
        $sql = "INSERT INTO qualification (employeeemail, qualification_name) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($qualifications as $qualification) {
            $stmt->bind_param('ss', $email, $qualification);
            $stmt->execute();
        }
        $stmt->close();
    }

    // Function to insert multiple experiences
    private function insertExperiences($conn, $email, $experiences)
    {
        $sql = "INSERT INTO experience (employeeemail, JobTitle) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($experiences as $experience) {
            $stmt->bind_param('ss', $email, $experience);
            $stmt->execute();
        }
        $stmt->close();
    }

    // Login method for authenticating users
    public function login($email, $password)
    {
        $sql = $this->conn->prepare("SELECT Email, Password FROM Employee WHERE Email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $storedPassword = $user['Password'];
            // Verify the password
            if (password_verify($password, $storedPassword)) {
                return true;
            }
        }
        return false;
    }

    //Get profile details
    public function getProfileDetails($email)
    {
        $sql = $this->conn->prepare("SELECT * FROM Employee WHERE Email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_assoc();
    }

    //  Get Current address
    public function getCurrentAddress($email)
    {
        $sql = $this->conn->prepare("SELECT address_id, line1, line2, city, state FROM currentaddress WHERE employeeemail = ? 
             AND (LENGTH(line1) > 0 OR LENGTH(line2) > 0 OR LENGTH(city) > 0 OR LENGTH(state) > 0)");
        $sql->bind_param("s", $email);
        $sql->execute();
        return $sql->get_result();
    }

    //Get Permanent address
    public function getPermanentAddress($email)
    {
        $sql = $this->conn->prepare("SELECT * FROM permanentAddress WHERE EmployeeEmail = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        return $sql->get_result();
    }

    // Get Qualifications
    public function getQualifications($email)
    {
        $sql = $this->conn->prepare("SELECT qualification_name, qualification_id FROM Qualification WHERE EmployeeEmail = ? AND LENGTH(qualification_name) > 0");
        $sql->bind_param("s", $email);
        $sql->execute();
        return $sql->get_result();
    }

    // Get Experiences
    public function getExperiences($email)
    {
        $sql = $this->conn->prepare("SELECT jobtitle, experience_id FROM Experience WHERE EmployeeEmail =? AND LENGTH(jobtitle) > 0");
        $sql->bind_param("s", $email);
        $sql->execute();
        return $sql->get_result();
    }

    // Profile Pic update
    public function profilepictureupdate($email, $profileImage)
    {
        $query = "UPDATE Employee SET ProfileImage = ? WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ss", $profileImage, $email);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    // Add qualification
    public function addQualification($email, $qualificationName)
    {
        $sql = "INSERT INTO Qualification (employeeEmail, qualification_name) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('ss', $email, $qualificationName);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }
    // Add experience
    public function addExperience($email, $jobTitle)
    {
        $query = "INSERT INTO Experience (employeeEmail, JobTitle) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('ss', $email, $jobTitle);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    public function addCurrentAddress($email, $line1, $line2, $city, $state)
    {
        $sql = "UPDATE currentaddress 
                SET line1 = ?, line2 = ?, city = ?, state = ? 
                WHERE employeeemail = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('sssss', $line1, $line2, $city, $state, $email);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }


    // Update Name
    public function nameUpdate($userId, $newName)
    {
        $query = "UPDATE Employee SET name = ? WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ss", $newName, $userId);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }
    // Update Qualification
    public function qualificationUpdate($qualification_id, $newQualification)
    {
        $stmt = $this->conn->prepare("UPDATE Qualification SET qualification_name = ? WHERE qualification_id = ?");
        $stmt->bind_param("si", $newQualification, $qualification_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result === true;
    }
    // Update Experience
    public function experienceUpdate($experience_id, $newExperience)
    {
        $query = "UPDATE Experience SET JobTitle = ? WHERE experience_id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("si", $newExperience, $experience_id);
            $result = $stmt->execute();
            $stmt->close();
            return $result === true;
        }
        return false;
    }
    // Update Current Address
    public function updateCurrentAddressLine1($addressId, $newLine1)
    {
        $stmt = $this->conn->prepare("UPDATE currentaddress SET line1 = ? WHERE address_id = ?");
        $stmt->bind_param("si", $newLine1, $addressId);
        $result = $stmt->execute();
        $stmt->close();
        return $result === true;
    }

    public function updateCurrentAddressLine2($addressId, $newLine2)
    {
        $stmt = $this->conn->prepare("UPDATE currentaddress SET line2 = ? WHERE address_id = ?");
        $stmt->bind_param("si", $newLine2, $addressId);
        $result = $stmt->execute();
        $stmt->close();
        return $result === true;
    }

    public function updateCurrentAddressCity($addressId, $newCity)
    {
        $stmt = $this->conn->prepare("UPDATE currentaddress SET city = ? WHERE address_id = ?");
        $stmt->bind_param("si", $newCity, $addressId);
        $result = $stmt->execute();
        $stmt->close();
        return $result === true;
    }

    public function updateCurrentAddressState($addressId, $newState)
    {
        $stmt = $this->conn->prepare("UPDATE currentaddress SET state = ? WHERE address_id = ?");
        $stmt->bind_param("si", $newState, $addressId);
        $result = $stmt->execute();
        $stmt->close();
        return $result === true;
    }
    // Update Permanent Address
    public function updatePermanentAddressLine1($addressId, $newLine1)
    {
        $query = "UPDATE permanentaddress SET line1 = ? WHERE address_id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("si", $newLine1, $addressId);
            $result = $stmt->execute();
            $stmt->close();
            return $result === true;
        }

        return false;
    }

    public function updatePermanentAddressLine2($addressId, $newLine2)
    {
        $query = "UPDATE permanentaddress SET line2 = ? WHERE address_id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("si", $newLine2, $addressId);
            $result = $stmt->execute();
            $stmt->close();
            return $result === true;
        }

        return false;
    }

    public function updatePermanentAddressCity($addressId, $newCity)
    {
        $query = "UPDATE permanentaddress SET city = ? WHERE address_id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("si", $newCity, $addressId);
            $result = $stmt->execute();
            $stmt->close();
            return $result === true;
        }

        return false;
    }

    public function updatePermanentAddressState($addressId, $newState)
    {
        $query = "UPDATE permanentaddress SET state = ? WHERE address_id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("si", $newState, $addressId);
            $result = $stmt->execute();
            $stmt->close();
            return $result === true;
        }

        return false;
    }
}
?>