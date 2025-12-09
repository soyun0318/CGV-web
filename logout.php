<?php
session_start();      
session_destroy();    
?>
<script>
    alert("로그아웃 되었습니다.");
    location.href = 'main_page.php'; 
</script>