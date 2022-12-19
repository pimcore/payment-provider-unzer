#!/bin/bash

set -eu -o xtrace

cp .github/ci/files/.env .
cp -r .github/ci/files/var var