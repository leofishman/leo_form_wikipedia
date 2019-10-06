1) Loads a page at /wiki which explains what this page does.
2) The page should include a 'Search' form field.
3) A user can either enter a value in the form field or provide a url parameter (/wiki/[parameter]).
4) If a URL parameter is provided then the page displays wikipedia articles containing the parameter in the title.
5) If no parameter is provided, then the page displays wikipedia articles for the term provided in the 'Search' form field.
6) The page should display the term that is being searched.
7) Search results should include the Title, a link to the article, and the extract for the article.
8) Your module should include functional tests and relevant documentation.
9) Check the module into github.


# Search form:
    - route /wiki/{parameter}
    - search, input text
    - description, markup explaining what this page does
    - term being search, input text disabled default value = query
    - title, tilte of the article found
    - link, link to wikipedia article found
    - extract extract of the article found

# Service guzzle http client to get wikipedia term
# template
    - hook alter for template suggestion
    - twig file to show the form

# Functional Tests
    - Happy path, know term and know result for title, link and extract
        - Empty search explaining how
        - query string in url
        - search form
    - random characters (including spetials characters) for not found
    - no connection to wikipedia
