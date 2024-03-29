<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRequestDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardResponseDto.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/board/BoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/board/BoardService.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/IndexBoardViewResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/board/IndexBoardFixResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    class BoardController {
        public function __construct() {
        }

        public function getIndexBoard() {
            try {
                $searchWord = filter_var(strip_tags($_GET['search']), FILTER_SANITIZE_SPECIAL_CHARS);
                $dateValue = filter_var(strip_tags($_GET['dateValue']), FILTER_SANITIZE_SPECIAL_CHARS);

                $numPerPage = 5;
                
                $pageNow = $_GET['page'] ? filter_var(strip_tags(intval($_GET['page'])), FILTER_SANITIZE_SPECIAL_CHARS) : 1;
                $blockNow = floor(($pageNow - 1) / $numPerPage) * $numPerPage;
                $startIndexPerPage = ($pageNow - 1) * $numPerPage;
                $sort = filter_var(strip_tags($_GET['sort']), FILTER_SANITIZE_SPECIAL_CHARS);

                $boardDto = new BoardRequestDto($searchWord, $dateValue, $numPerPage, $startIndexPerPage, $sort);


                $boardService = new BoardService();
                $boardServiceResponse = $boardService->getIndexBoard($boardDto);
                $totalPages = $boardServiceResponse->getTotalPages();
                $boardData = $boardServiceResponse->getBoardData();
                $indexBoardResponse = new IndexBoardResponse($searchWord, $dateValue, $pageNow, $blockNow, $sort, $totalPages, $boardData);
                return $indexBoardResponse;
            } catch (Exception $e) {
                echo "<script>alert('게시판을 가져오는 도중에 문제가 발생했습니다!');</script>";
                echo "<script>location.replace('/application/view/login/login.html');</script>";
            }
            
        }

        public function getIndexBoardFix() {
            $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
            try {
                $boardService = new BoardService();
                $indexBoardFixResponse = $boardService->getIndexBoardFix($boardId);
                return $indexBoardFixResponse;
            } catch (Exception $e) {
                echo "<script>alert('수정폼을 가져오는 도중에 문제가 발생했습니다!');</script>";
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }
        }

        public function getIndexBoardView() {
            $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
            try {
                $boardService = new BoardService();
                $data = $boardService->getIndexBoardView($boardId);
                $indexBoardViewResponse = new IndexBoardViewResponse($boardId, $data);
                return $indexBoardViewResponse;
            } catch (Exception $e) {
                echo "<script>alert('게시글을 가져오는 도중에 문제가 발생했습니다!');</script>";
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }
        }

        public function writeIndexBoard($title, $body, $userId, $file) {
            try {
                $today = date("Y-m-d");

                $boardService = new BoardService();
                $boardId = $boardService->write($title, $body, $userId, $today, $file);
                return $boardId;
            } catch (Exception $e) {
                throw $e;
            }
        }

        public function fixIndexBoard($boardId, $title, $body, $userId, $file) {
            try {
                $today = date("Y-m-d");

                $boardService = new BoardService();
                $boardService->fix($boardId, $title, $body, $userId, $today, $file);
            } catch (IdNotMatchedException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        public function getComment($boardId) {
            try {
                $boardService = new BoardService();
                $rows = $boardService->getComment($boardId);
                return $rows;
            } catch (Exception $e) {
                echo "<script>alert('댓글을 가져오는 도중에 문제가 발생했습니다!');</script>";
                echo "<script>location.replace('/index.php');</script>";
            }
        }
    }    
?>