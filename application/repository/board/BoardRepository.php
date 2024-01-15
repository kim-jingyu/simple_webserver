<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/application/connection/DBConnectionUill.php';

    class BoardRepository {
        public function __construct() {
        }

        public function updateId($newId, $oldId) {
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "UPDATE board SET user_id = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newId, $oldId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Update UserId At Board - DB Exception 발생!");
            } finally {
                close($conn, $stmt);
            }
        }

        public function pagenate(BoardDto $boardDto) {
            try {
                $conn = DBConnectionUtil::getConnection();
                $cntSql = "SELECT count(*) AS cnt FROM board WHERE title LIKE ? AND date_value LIKE ?";
                $stmt = prepared_query($conn, $cntSql, [$boardDto->getSearchWord(), $boardDto->getDateValue()]);
                $totalCnt = $stmt->get_result()->fetch_assoc()['cnt'];

                $selectSql = "SELECT * FROM board WHERE title LIKE ? AND date_value like ?";
                if ($boardDto->getSort() != null) {
                    $sort = $boardDto->getSort();
                    if ($sort == 'author') {
                        $selectSql .=  " order by user_id desc";
                    } else if ($sort == 'date') {
                        $selectSql .= " order by date_value desc";
                    } else if ($sort == 'views') {
                        $selectSql .= " order by views desc";
                    } else if ($sort == 'likes') {
                        $selectSql .= " order by likes desc";
                    } else {
                        $selectSql .= " order by id desc";
                    }
                }
                $selectSql .= " limit ?, ?";

                $stmt = prepared_query($conn, $selectSql, [$boardDto->getSearchWord(), $boardDto->getDateValue(), $boardDto->getStartIndexPerPage(), $boardDto->getNumPerPage()]);
                $result = $stmt->get_result();

                return $result;
            } catch (Exception $e) {
                throw new Exception("Pagnation At Board - DB Exception 발생!");
            } finally {
                close($conn, $stmt);
            }
        }

        private function close($conn, $stmt) {
            if ($stmt != null) {
                $stmt->close();
            }

            if ($conn != null) {
                $conn->close();
            }
        }
    }
?>