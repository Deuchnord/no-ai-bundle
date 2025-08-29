# Debugging tools

This folder contains some utilities that can be useful for debugging.
They are not distributed with the bundle and are not meant to be used in production.

## Honeypot

The `honeypot` folder contains a script that logs any Web request received.

### How to use it?

1. Go into the `/utils/honeypot` folder
2. Run `composer install` (or `composer update` if already installed)
3. Run the PHP server with `php -s -S 0.0.0.0:8000` (feel free to change the port if needed)
4. Expose your webserver to the Internet with a software like [ngrok](https://ngrok.com/)
5. Ask the LLM you want to check to access the exposed URL (for instance: ask it to summary it)
6. Look at the app logs in the terminal, you should see new lines:
   ```
   [Fri Aug 29 10:07:37 2025] [info] Request from x.x.x.x; User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.7151.55 Safari/537.36
   ```
   
> [!Note]
> - If the LLM crawler calls `robots.txt`, it is also logged for information purposes.
> - Some LLMs may pretend that they cannot access it, but it might be a lie. Don't trust them, only look at the logs.
> - The NoAiBundle is enabled on the honeypot, so any request from a supported LLM should be blocked with a 403 response. If it's not, please open an issue.
