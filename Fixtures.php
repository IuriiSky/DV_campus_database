<?php
declare(strict_types=1);
class Fixtures
{
    /**
     * @var PDO $connection
     */
    private static $connection;
    /**
     * @return void
     */
    public function generate(): void
    {
        $connection = $this->getConnection();
        try {
            $connection->beginTransaction();
            $this->cleanup();
            $connection->commit();
            $connection->beginTransaction();
            $this->generateEmployees(55);
            $this->generateSalary(10000);
            $this->generateTransport(25);
            $this->generateProfit(100000);
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            echo $e->getMessage();
        }
    }

    private function getRandomName(): string
    {
        static $randomNames = ['Norbert','Damon','Laverna','Annice','Brandie','Emogene','Cinthia','Magaret','Daria','Ellyn','Rhoda','Debbra','Reid','Desire','Sueann','Shemeka','Julian','Winona','Billie','Michaela','Loren','Zoraida','Jacalyn','Lovella','Bernice','Kassie','Natalya','Whitley','Katelin','Danica','Willow','Noah','Tamera','Veronique','Cathrine','Jolynn','Meridith','Moira','Vince','Fransisca','Irvin','Catina','Jackelyn','Laurine','Freida','Torri','Terese','Dorothea','Landon','Emelia'];
        return $randomNames[array_rand($randomNames)];
    }

    private function getRandomSurname(): string
    {
        static $randomSurnames = ['Knyazev','Belokopytov','Zurov','Lundyshev','Alabin','Repnin','Chesnok','Aleksandrovich','Kriger','Komarovskiy','Varpahovskiy','Haritonov','Streshnev','Apostol','Chagin','Birkin','Melniczkiy','Kusakov','Aristov','Bazilevskiy','Starovoytov','Kolosovskiy','Danilov','Pavlov','Voevodskiy','Shherban','Gorstkin','Masleniczkiy','Boltov','Ogibalov','Berdyaev','Tatishhev','Rahmaninov','Korolenko','Kalugin','Romodanovskiy','Yakimov','Shahmatov','Raslovlev','Isaykin','Novikov','Shpigel','Sablukov','Grigorovich','Diveev','Chufarovskiy','Shihmatov','Gedeonov','Alyabev','Patrikeev'];
        return $randomSurnames[array_rand($randomSurnames)];
    }


    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        if (null === self::$connection) {
            self::$connection = new PDO('mysql:host=127.0.0.1:3357;dbname=CherkasyElektroTrans', 'CherkasyElektroTrans', 'CherkasyElektroTrans', []);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$connection;
    }

    private function cleanup(): void
    {
        $connection = $this->getConnection();
        $connection->exec('DELETE FROM employee');
        $connection->exec('ALTER TABLE employee AUTO_INCREMENT = 1');
        $connection->exec('DELETE FROM salary');
        $connection->exec('ALTER TABLE salary AUTO_INCREMENT = 1');
        $connection->exec('DELETE FROM result_profit');
        $connection->exec('ALTER TABLE result_profit AUTO_INCREMENT = 1');
        $connection->exec('DELETE FROM transport');
        $connection->exec('ALTER TABLE transport AUTO_INCREMENT = 1');

    }
    /**
     * @param int $usersCount
     * @throws Exception
     */
    
    public function generateEmployees(int $usersCount): void
    {
        $connection = $this->getConnection();
        $currentTimestamp = time();

        // === CREATE USERS ===

        $start = microtime(true);
        $employeeId = $employeeName = $employeeSurname = $dob = $positionId = $currentSalary = '';
        $minAgeTimestamp = $currentTimestamp - (31556952 * 45);
        $maxAgeTimestamp = $currentTimestamp - (31556952 * 16);
        $statement = $connection->prepare(<<<SQL
    INSERT INTO employee (employee_id, employee_name, employee_surname, dob, position_id, current_salary)
    VALUES (:employeeId, :employeeName, :employeeSurname, :dob, :positionId, :currentSalary)
SQL
        );
        $statement->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
        $statement->bindParam(':employeeName', $employeeName);
        $statement->bindParam(':employeeSurname', $employeeSurname);
        $statement->bindParam(':dob', $dob);
        $statement->bindParam(':positionId', $positionId, PDO::PARAM_INT);
        $statement->bindParam(':currentSalary', $currentSalary, PDO::PARAM_INT);
        for ($employeeId = 1; $employeeId <= $usersCount; $employeeId++) {
            $employeeName = $this->getRandomName();
            $employeeSurname = $this->getRandomSurname();
            $timestamp = random_int($minAgeTimestamp, $maxAgeTimestamp);
            $dob = date('Y-m-d', $timestamp);
            $positionId  = random_int(1, 7);
            $currentSalary = random_int(5000, 20000);
            $statement->execute();
        }

        echo 'Create users: ' . (microtime(true) - $start) . "\n";
    }

    /**
     * @param int $salaryPayment
     * @throws Exception
     */

    public function generateSalary(int $salaryPayment): void
    {
        $connection = $this->getConnection();
        $currentTimestamp = time();

        // === CREATE SALARY ===

        $start = microtime(true);
        $paymentSalaryId = $datePayment = $amountPayment = $employeeId = $positionId = '';
        $minDatePayment = $currentTimestamp - (31556952 * 5);
        $maxDatePayment = $currentTimestamp - (31556952 * 1);
        $statement = $connection->prepare(<<<SQL
    INSERT INTO salary (payment_of_salary_id, date_of_payment, amount_of_payment, employee_id, position_id)
    VALUES (:paymentSalaryId, :datePayment, :amountPayment, :employeeId, :positionId)
SQL
        );
        $statement->bindParam(':paymentSalaryId', $paymentSalaryId, PDO::PARAM_INT);
        $statement->bindParam(':datePayment', $datePayment);
        $statement->bindParam(':amountPayment', $amountPayment, PDO::PARAM_INT);
        $statement->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
        $statement->bindParam(':positionId', $positionId, PDO::PARAM_INT);
        for ($paymentSalaryId = 1; $paymentSalaryId <= $salaryPayment; $paymentSalaryId++) {
            $timestamp = random_int($minDatePayment, $maxDatePayment);
            $datePayment = date('Y-m-d', $timestamp);
            $amountPayment = random_int(5000, 20000);
            $employeeId = random_int(1, 55);
            $positionId = random_int(1, 7);
            $statement->execute();
        }
        echo 'Create users: ' . (microtime(true) - $start) . "\n";
    }

    /**
     * @param int $countTransport
     * @throws Exception
     */

    public function generateTransport(int $countTransport): void
    {
        $connection = $this->getConnection();

        // === CREATE Transport ===

        $start = microtime(true);
        $transportId = $licensePlate = '';
        $statement = $connection->prepare(<<<SQL
    INSERT INTO transport (transport_id, license_plate)
    VALUES (:transportId, :licensePlate)
SQL
        );
        $statement->bindParam(':transportId', $transportId, PDO::PARAM_INT);
        $statement->bindParam(':licensePlate', $licensePlate);
        for ($transportId = 1; $transportId <= $countTransport; $transportId++) {
            $licensePlate = 'CA' . '' . random_int(0000, 9999) . '' . 'AC';
            $statement->execute();
        }
        echo 'Create users: ' . (microtime(true) - $start) . "\n";
    }

    /**
     * @param int $income
     * @throws Exception
     */

    public function generateProfit(int $income): void
    {
        $connection = $this->getConnection();
        $currentTimestamp = time();

        // === CREATE RESULT PROFIT ===

        $start = microtime(true);
        $profitId = $dateWorked = $employeeId = $transportId = $profit = '';
        $minDateWorked = $currentTimestamp - (31556952 * 5);
        $maxDateWorked = $currentTimestamp - (31556952 * 1);
        $statement = $connection->prepare(<<<SQL
    INSERT INTO result_profit (result_profit_id , date, employee_id, transport_id, profit)
    VALUES (:profitId, :dateWorked, :employeeId, :transportId, :profit)
SQL
        );
        $statement->bindParam(':profitId', $profitId, PDO::PARAM_INT);
        $statement->bindParam(':dateWorked', $dateWorked);
        $statement->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
        $statement->bindParam(':transportId', $transportId, PDO::PARAM_INT);
        $statement->bindParam(':profit', $profit, PDO::PARAM_INT);
        for ($profitId = 1; $profitId <= $income; $profitId++) {
            $timestamp = random_int($minDateWorked, $maxDateWorked);
            $dateWorked = date('Y-m-d', $timestamp);
            $employeeId = random_int(1, 55);
            $transportId = random_int(1, 25);
            $profit = random_int(1000, 5000);
            $statement->execute();
        }
        echo 'Create users: ' . (microtime(true) - $start) . "\n";
    }

}
$fixturesGenerator = new Fixtures();
$fixturesGenerator->generate();
