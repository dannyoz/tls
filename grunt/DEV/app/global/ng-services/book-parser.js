//Factory for parsing the custom fields for books into a  more manageble format

.factory('books', [ "$q", function ($q){
	return{
		parse : function(fields){

			var defer     = $q.defer(),
				bookcount = fields.books[0],
				books     = [];

			for(var i = 0; i<bookcount; i ++){
				
				var book = {},
                    title   = "books_" + i + "_book_title",
					author  = "books_" + i + "_book_author",
					info   = "books_" + i + "_book_info",
					isbn    = "books_" + i + "_book_isbn";

                book.title  = fields[title][0]
				book.author = fields[author][0]
				book.info1  = fields[info][0]
				book.isbn   = fields[isbn][0]

				books.push(book)
			}

			defer.resolve(books);
			return defer.promise
		}
	}
}])