포트폴리오
=======
게시판을 설치하고 관리할 수 있는 간단한 웹 프로그램입니다. (Codeigniter 3.0.x 기반)

테스트 페이지
==========
<http://gomm.asuscomm.com/board>

관리자 아이디 : admin   
비밀번호 : 1111   
게스트 아이디 : guest   
비밀번호 : 1111   
게스트 이메일 : guest12341111@gmail.com (아이디 찾기나 비밀번호 변경시 이용)   
비밀번호 : g12341111  

매뉴얼
=====
<https://gommmm.github.io/>

설치 요구조건
==========
1. PHP 5.3.7 이상 필요
2. GD Library 2.0 이상 필요
3. Node.js 필요  

설치 전 주의사항
============

1. 최상위 폴더의 권한을 777 또는 707로 설정한다.   

2. 폴더를 생성하고 그 안에 파일을 넣을 시, 예를 들어 board 폴더 안에 파일들을 넣을 때  

3. application/config/constants.php 90번째 줄 SUB_DIRECTORY 상수에 경로 추가   
<pre><code>define('SUB_DIRECTORY', '/board');</code></pre>

4. resource/plugin/seditor/sample/photo_uploader/file_uploader_html5.php 31번째 줄 변수에 경로 추가   
<pre><code>$sub = '/board';</code></pre>

5. resource/plugin/seditor/sample/photo_uploader/attach_photo.js 336번째 줄 경로 추가   
<pre><code>var sub = "/board";</code></pre>

설치 방법
=======
1. 다운받은 파일을 원하는 웹서버 폴더에 넣고 주소를 입력하면 설치하라는 페이지가 나오는데 설치를 눌러줍니다.
2. 그러면 데이터베이스 정보 및 관리자 정보를 입력하는 폼이 나오는데 입력해줍니다.
3. 데이터베이스 정보를 올바르게 입력하면 설치가 완료됐다는 화면이 나오고 설치가 완료됩니다.
4. 이제 모두 설치를 완료했습니다. 메인페이지로 링크를 눌러줍니다.
