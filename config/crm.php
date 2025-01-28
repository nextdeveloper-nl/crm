<?php

return [
    'scopes'    =>  [
        'global' => [
            //  Dont do this here because it makes infinite loop with user object.
            '\NextDeveloper\IAM\Database\Scopes\AuthorizationScope',
            '\NextDeveloper\Commons\Database\GlobalScopes\LimitScope',
        ]
    ],

    'linked_actions'    =>  [
        'created:NextDeveloper\CRM\QuoteItems' => \NextDeveloper\CRM\Actions\QuoteItems\ValidateQuoteItem::class
    ]
];
