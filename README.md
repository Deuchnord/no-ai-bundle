# No AI Bundle

A simple Symfony bundle that blocks large language model (LLM) crawlers.
When an LLM tries to load a page of your website, a `403 Forbidden` will be automatically returned before your code has even run, saving resources on your server.

## Caveats

Before you install this bundle, be sure you are aware of the following:

- For now, this bundle relies on the User-Agent declared by crawlers. This seems enough, but we might need IP address blocking in the future if they try to cheat on it.
- Some companies use the same User-Agent both for LLM crawling and for other tasks. Using this bundle may have more or less negative impacts: 
  - Microsoft: Bing search engine will deindex your pages, visitors won't be able to find your pages anymore.
  - Amazon: if you sell products on your website, Amazon won't be able to recommend them anymore.

## Requirements

To use this bundle, you will need to run:

- PHP 8.3+
- Symfony 6.4+

## Installation

In your Symfony project, install the `deuchnord/no-ai-bundle` package:

```bash
composer require deuchnord/no-ai-bundle
```

Once installed, the bundle will already be configured to block any request from an LLM crawler.
You can test it with a Curl command (here with ChatGPT crawler):

```bash
 curl -v \ 
      --url http://localhost/ \ # change the URL here
      --header 'User-Agent: Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko); compatible; GPTBot/1.1; +https://openai.com/gptbot)'
```

Your project will respond a blank page along with a `403 Forbidden` status code.

> [!NOTE]
> Each time a request is blocked for LLM crawler detection, an event is dispatched. Feel free to listen to it if you need it:
> 
> ```php
> <?php
> 
> namespace App\EventListener;
> 
> use Deuchnord\NoAiBundle\Event\LlmCrawlerBlockedEvent;
> use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
> 
> #[AsEventListener(event: LlmCrawlerBlockedEvent::class)]
> class LlmCrawlerBlockedEventListener
> {
>     public function __invoke(LlmCrawlerBlockedEvent $event)
>     {
>         // Your logic here.
>     }
> }
> ```

## Configuration

For now, there is no configuration option. This might change in the future.


## FAQ

### Why this bundle?

Despite the hype around generative AI, everybody is not comfortable with this technology for a lot of excellent reasons: ethics, environment, copyright...

This bundle helps people who don't want LLM crawlers visit their website to easily block it.

### What about `robots.txt`?

The `robots.txt` file tells robots what they can visit or not, but it has a flaw: it depends on the good will of the companies who run them to respect your instructions. You can still use it if you trust them enoug. 
If you don't, then you need a more aggressive way to block them. 

### Can I allow some crawlers to visit my website?

Such a feature is not planned. If you really want it, the best solution would be to decorate the `Deuchnord\NoAiBundle\LlmDetection` service.

### I am the CEO of a generative AI company and I hate this bundle

Noted. What's your question, again?