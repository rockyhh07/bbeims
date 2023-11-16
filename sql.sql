SELECT
    a.incident_date `date`,
    e.lname,
    e.fname,
    e.mname,
    e.contact,
    e.birthday,
    e.gender,
    e.civil_status,
    e.address,
    e2.lname `repLname`,
    e2.fname `repFname`,
    e2.mname `repMname`,
    c.name `center`,
    i.name `incident`
FROM `evacuee`                      e
INNER JOIN `incident_archive`       a
    ON e.`id` = a.`evacuee_id`
INNER JOIN `evacuee`                e2
    ON e2.`id` = e.`representative`
INNER JOIN `evac_center`            c
    ON c.`id` = a.`evac_id`
INNER JOIN `incident`               i
    ON i.`id` = a.`incident_id`
WHERE e.`deletedflag` = '0' AND i.`id` = '1'





SELECT
    e.incident_date `date`,
    e.lname,
    e.fname,
    e.mname,
    e.contact,
    e.birthday,
    e.gender,
    e.civil_status,
    e.address,
    i.name `center`,
    c.name `incident`,
    r.lname `repLname`,
    r.fname `repFname`,
    r.mname `repMname`
FROM `evacuee`              e
INNER JOIN `incident`       i
    ON i.id = e.`incident_id`
INNER JOIN `evac_center`    c
    ON c.id = e.`evac_id`
INNER JOIN `evacuee`        r
    ON r.`id` = e.`representative`
WHERE e.`deletedflag` = '0' AND e.`incident_id` = '1'
UNION (
    SELECT
        a.`incident_date` `date`,
        e.`lname`,
        e.`fname`,
        e.`mname`,
        e.`contact`,
        e.`birthday`,
        e.`gender`,
        e.`civil_status`,
        e.`address`,
        i.`name` `center`,
        c.`name` `incident`,
        r.lname `repLname`,
        r.fname `repFname`,
        r.mname `repMname`
    FROM `incident_archive`     a
    INNER JOIN `evacuee`        e
        ON e.`id` = a.`evacuee_id`
    INNER JOIN `incident`       i
        ON i.id = a.`incident_id`
    INNER JOIN `evac_center`    c
        ON c.id = a.`evac_id`
    INNER JOIN `evacuee`        r
    ON r.`id` = e.`representative`
    WHERE a.`deletedflag` = '0' AND a.`incident_id` = '1'
)