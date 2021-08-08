-- 1. Write a query to display the ff columns 
--     ID (shouldstartwith T + 11 digits of trn_teacher.id with leading zeroslike'T00000088424'),
--     Nickname,
--     Status 
--     and Roles (like Trainer/Assessor/Staff) using table trn_teacher and trn_teacher_role.

SELECT 
    LPAD(te.id, 12, 'T00000000000') AS 'ID',
    te.nickname AS 'Nickname',
    CASE
        WHEN te.status = 0 THEN 'Discontinued'
        WHEN te.status = 1 THEN 'Active'
        WHEN te.status = 2 THEN 'Deactivated'
    END AS 'Status',
    GROUP_CONCAT(DISTINCT CASE
            WHEN tr.role = 1 THEN 'Trainer'
            WHEN tr.role = 2 THEN 'Assessor'
            WHEN tr.role = 3 THEN 'Staff'
        END
        SEPARATOR ', ') AS 'Roles'
FROM
    trn_teacher AS te
        LEFT JOIN
    trn_teacher_role AS tr ON tr.teacher_id = te.id
GROUP BY te.id  

-- 2. Write a query to display the ff columns
--     ID (from teacher.id),Nickname,
--     Open (total open slots from trn_teacher_time_table),
--     Reserved (total reserved slots from trn_teacher_time_table),
--     Taught (total taught from trn_evaluation)
--     and NoShow (total no_show from trn_evaluation) using all tables above.
--     Should show only those who are active (trn_teacher.status = 1or2) and those who have both Trainer and Assessor role. 

SELECT 
    te.id AS 'ID',
    te.nickname AS 'Nickname',
    (SELECT 
            COUNT(*)
        FROM
            trn_time_table AS ti
        WHERE
            ti.teacher_id = te.id AND ti.status = 1) AS 'Open',
    (SELECT 
            COUNT(*)
        FROM
            trn_time_table AS ti
        WHERE
            ti.teacher_id = te.id AND ti.status = 3) AS 'Reserved',
    (SELECT 
            COUNT(*)
        FROM
            trn_evaluation AS ev
        WHERE
            ev.teacher_id = te.id AND ev.result = 1) AS 'Taught',
    (SELECT 
            COUNT(*)
        FROM
            trn_evaluation AS ev
        WHERE
            ev.teacher_id = te.id AND ev.result = 2) AS 'No Show'
FROM
    trn_teacher AS te
        LEFT JOIN
    trn_teacher_role AS tr ON tr.teacher_id = te.id
WHERE
    te.status IN (1 , 2)
        AND tr.role IN (1 , 2)
GROUP BY te.id
