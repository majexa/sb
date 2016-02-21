<?php

return [
  'extends'      => 'common',
  'vhostAliases' => [
    '/sb/'  => '{ngnEnvPath}/sb/static/',
    '/cpm/' => '{ngnEnvPath}/sb/lib/cpm/'
  ],
  'afterCmdTttt' => [
    'php {runPath}/run.php site {name} sb/preInstall',
    'php {runPath}/run.php site {name} sb/install'
  ]
];
