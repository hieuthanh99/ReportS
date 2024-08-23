npm install && npm run dev




 Schema::create('task_target', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->string('code', 50);
            $table->string('name', 1000);
            $table->string('cycle_type', 50);
            $table->foreignId('category_id')->constrained('categories')->onDelete('set null');
            $table->text('request_results')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('creator');
            $table->enum('status', ['new', 'assign', 'complete', 'reject']);
            $table->text('results')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('set null');
            $table->enum('type', ['task', 'target']);
            $table->timestamps();
        });