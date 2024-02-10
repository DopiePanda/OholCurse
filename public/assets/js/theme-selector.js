
if (userDarkmode == 'enabled' || userDarkmode == 'disabled') 
{
    if (userDarkmode == 'enabled')
    {
        document.documentElement.classList.add('dark')
        console.log('User selected darkaaa');
    } else
    {
        document.documentElement.classList.remove('dark')
        console.log('User selected lightaaa');
    }

    console.log(userDarkmode);

} else 
{
    if(window.matchMedia('(prefers-color-scheme: dark)').matches)
    {
        document.documentElement.classList.add('dark')
        console.log('User system dark');
    } else
    {
        if(defaultDarkmode == 'dark')
        {
            document.documentElement.classList.add('dark')
            console.log('Default theme dark');
        } else
        {
            document.documentElement.classList.remove('dark')
            console.log('Default theme light');
        }
    }
}