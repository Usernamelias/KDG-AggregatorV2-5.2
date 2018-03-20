<ol>
    <li>
    <strong>Log into your Zoho Projects account.</strong>
    </li>

    <li>
    Click <a href="https://accounts.zoho.com/apiauthtoken/create?SCOPE=ZohoProjects/projectsapi,ZohoPC/docsapi" target='_blank'>here </a>
    to generate your auth token. 
    </li>

    <li>
    Once there, you should see something like "AUTHTOKEN=3490urfjdlo03490weop" on the page.
    </li>

    <li>
    Copy the string after "AUTHTOKEN=" and paste it into the form below.
    </li>
    @if(\Request::is('authtoken'))
        <li>
        Hit submit. The page should redirect you afterward if everything was successful.
        </li>

        <li>   
        You can start entering time entries.
        </li>
    @elseif(\Request::is('settings'))
        <li>
        Hit submit.
        </li>
    @endif
</ol>