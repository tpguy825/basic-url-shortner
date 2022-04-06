### Basic URL shortner
This is a basic url shortned that I made because I got bored
## Instalation
# Windows
For this you will need:
- A windows computer (obviously)
- [XAMPP](https://www.apachefriends.org/download.html)
- Some hard drive space
Steps:
1. Install XAMPP in a folder you will remember
2. Go to the folder that you installed it in
3. Open `htdocs`
4. Open a command prompt there (in explorer you can type `cmd` in the location box)
5. Type in `git clone https://github.com/tpguy825/basic-url-shortner url`
6. Open `url` and then `config.json`
7. Change these values:
     - `autohttpsredirect` to `false`
     - `domain` to `localhost`
     - `folder` to whatever folder this is in (for example if it is in C:\XAMPP\htdocs\urlshortner then set it to `urlshortner`) Note: if this is wrong, it will break
     - `autoaddhttp` to `true`

8. Open XAMPP control panel (it might already be opn, but if it isn't, open it)
9. At the top, under modules, click `start` next to Apache
10. Then, go to `http://localhost/folder` (replace `folder` with whatver folder it is in)
11. Done!

