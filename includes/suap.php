<?php
// includes/suap.php
// Autenticação via SUAP OAuth2 (Authorization Code Grant)

require_once __DIR__ . '/config.php';

function suapBaseUrl()
{
    return rtrim(env('SUAP_BASE_URL', 'https://suap.ifgoiano.edu.br'), '/');
}

function suapClientId()
{
    return env('SUAP_CLIENT_ID', '');
}

function suapClientSecret()
{
    return env('SUAP_CLIENT_SECRET', '');
}

function suapRedirectUri()
{
    return env('SUAP_REDIRECT_URI', '');
}

function suapAuthorizeUrl($state)
{
    $params = [
        'response_type' => 'code',
        'client_id'     => suapClientId(),
        'redirect_uri'  => suapRedirectUri(),
        'state'         => $state,
    ];
    return suapBaseUrl() . '/o/authorize/?' . http_build_query($params);
}

function suapHttpStatus(array $headers)
{
    foreach ($headers as $header) {
        if (preg_match('#^HTTP/\S+\s+(\d+)#', $header, $m)) {
            return (int)$m[1];
        }
    }
    return 0;
}

function suapPostForm($url, array $fields)
{
    $context = stream_context_create([
        'http' => [
            'method'        => 'POST',
            'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content'       => http_build_query($fields),
            'ignore_errors' => true,
            'timeout'       => 15,
        ],
    ]);
    $body = @file_get_contents($url, false, $context);
    $status = suapHttpStatus($http_response_header ?? []);
    return [$status, $body];
}

function suapGetJson($url, $accessToken)
{
    $context = stream_context_create([
        'http' => [
            'method'        => 'GET',
            'header'        => "Authorization: Bearer $accessToken\r\n",
            'ignore_errors' => true,
            'timeout'       => 15,
        ],
    ]);
    $body = @file_get_contents($url, false, $context);
    $status = suapHttpStatus($http_response_header ?? []);
    return [$status, $body];
}

function suapExchangeCodeForToken($code)
{
    [$status, $body] = suapPostForm(suapBaseUrl() . '/o/token/', [
        'grant_type'    => 'authorization_code',
        'code'          => $code,
        'redirect_uri'  => suapRedirectUri(),
        'client_id'     => suapClientId(),
        'client_secret' => suapClientSecret(),
    ]);

    if ($status !== 200 || !$body) {
        return null;
    }

    $data = json_decode($body, true);
    return $data['access_token'] ?? null;
}

function suapGetUserInfo($accessToken)
{
    [$status, $body] = suapGetJson(suapBaseUrl() . '/api/eu/', $accessToken);

    if ($status !== 200 || !$body) {
        return null;
    }

    return json_decode($body, true);
}
