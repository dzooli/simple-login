<?php

namespace App\Models;

use \PDO;
use Framework\Myy;
use Framework\Model;

class UserPage extends Model
{

    protected array $pages = [];

    public static function findPagesFor(int $id = 0): ?array
    {
        parent::preCheck();
        $dbconn = Myy::$app->getDb();

        $stmt = $dbconn->prepare(
            'SELECT DISTINCT
                pag.name
            FROM
                user u
                    LEFT JOIN
                user_has_role ur ON u.id = ur.user_id
                    LEFT JOIN
                role rol ON ur.role_id = rol.id
                    LEFT JOIN
                role_has_page rp ON rol.id = rp.role_id
                    LEFT JOIN
                page pag ON rp.page_id = pag.id
            WHERE
                u.id = :user_id;'
        );

        $dbDone = $stmt->execute([':user_id' => $id]);
        if ($dbDone) {
            $res = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        return ($res && is_array($res) && count($res) > 0 && $res[0] !== null) ? array_values($res) : null;
    }
}
