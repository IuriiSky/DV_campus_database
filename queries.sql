-- # Queries 1
SELECT * from employee
    ORDER BY employee_surname;

-- # Queries 2

SELECT e.employee_name, e.employee_surname,
       AVG(s.amount_of_payment) AS average_amount
FROM employee AS e
        INNER  JOIN salary AS s ON  e.employee_id = s.employee_id
GROUP BY s.employee_id;

-- # Queries 3
SELECT p.position,
       MAX(s.amount_of_payment) as max_payment,
       AVG(s.amount_of_payment) AS avarage_amount
FROM salary AS s
        INNER  JOIN position AS p ON s.position_id = p.position_id
GROUP BY p.position_id

-- Queries 4
SELECT e.employee_name, e.employee_surname,
    COUNT(*) AS worked_days,
       SUM(r.profit) AS total_income
FROM result_profit AS r
        INNER JOIN employee AS e ON r.employee_id = e.employee_id
GROUP BY r.employee_id

-- Queries 5
SELECT t.license_plate,
    SUM(r.profit) AS total_income,
       AVG(r.profit) AS average_income,
    COUNT(*) AS worked_days
FROM result_profit AS r
        INNER JOIN transport AS t ON r.transport_id = t.transport_id
GROUP BY r.transport_id
ORDER BY worked_days DESC

--Queries 6
SELECT *
    FROM employee WHERE MONTH(employee.dob) = 5;

-- Queries 7
SELECT e.employee_name, e.employee_surname,
       DATEDIFF(CURDATE(), MIN(s.date_of_payment)) / 365.0 AS years
FROM employee AS e
    INNER JOIN salary AS s ON e.employee_id = s.employee_id
GROUP BY s.employee_id;
