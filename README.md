# problem-bear-compile-serialize

## 現象

`bear.compile` をすると以下ワーニングが表示される。

```text
PHP Warning:  Failed to verify the injector cache. See https://github.com/bearsunday/BEAR.Package/issues/418 in /home/runner/work/problem-bear-compile-serialize/problem-bear-compile-serialize/source/app/vendor/bear/package/src/Injector/PackageInjector.php on line 59
```

**GitHub Actions**

`shivammathur/setup-php@v2` を使って `PHP8.1` で実行

![github-actions.png](.screenshot%2Fgithub-actions.png)

→ ワーニングが表示される

**Local**

`docker` を使い `PHP8.1` で実行

![local-docker.png](.screenshot%2Flocal-docker.png)

→ ワーニングが表示されない
