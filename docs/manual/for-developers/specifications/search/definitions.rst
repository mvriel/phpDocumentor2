Definitions
===========

Backend
    One of the two parts of the Search Engine, executes queries from the Frontend and returns the raw results to
    the Frontend

Builder
    One of the two parts of the Search Engine, converts the data presented by the Provider into a searchable format,
    or index.

Consumer
    Also known as the User, the actor who wants to query the system.

Document
    A searchable collection of information such as a manual page, an element in the API, etc.

Expression
    Input by the Consumer that can be fed into the Search Engine's Backend. May contain a series of terms and operators
    to limit the result set by.

Frontend
    Accepts queries from the User, sends them to the Backend and presents the results in a clear usable way to the User.

Provider
    Part of the system that populates the Search Engine with Documents and data on which to search (fields).

Search Engine
    A system that enables the Frontend to execute queries and receive results.
    Usually consists of 2 distinct parts, the Builder and Backend.

