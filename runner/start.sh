#/usr/bin
if ! command -v php &> /dev/null
then
    echo "php command not found"
    exit
fi
nohup php bridge-server.php > access.log 2>&1 &
echo $! > pid