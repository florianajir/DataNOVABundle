# Records local storage

## Download command

```shellScript
Usage:
  datanova:download:dataset [options] [--] <dataset> [<format>] [<q>]

Arguments:
  dataset               Which dataset to download?
  format                Data file format : CSV (default), JSON [default: "CSV"]
  q                     query filter, by default all results will be download

Options:
  -f, --force-replace   If set, the command will replace local storage
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -e, --env=ENV         The Environment name. [default: "dev"]
      --no-debug        Switches off debug mode.
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
 Download dataset records to use it locally
```
