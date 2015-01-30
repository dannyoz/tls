//Factory for parsing the custom fields for books into a  more manageble format

.factory('books', [ "$q", function ($q){
	return{
		parse : function(fields){

			var defer     = $q.defer(),
				bookcount = fields.books[0],
				books     = [];

			for(var i = 0; i<bookcount; i ++){
				
				var book = {},
					author  = "books_" + i + "_book_author",
					info1   = "books_" + i + "_book_info_1",
					info2   = "books_" + i + "_book_info_2",
					isbn    = "books_" + i + "_book_isbn",
					title   = "books_" + i + "_book_title";

				book.author = fields[author][0]
				book.info1  = fields[info1][0]
				book.info2  = fields[info2][0]
				book.isbn   = fields[isbn][0]
				book.title  = fields[title][0]

				books.push(book)
			}

			defer.resolve(books);
			return defer.promise
		}
	}
}])